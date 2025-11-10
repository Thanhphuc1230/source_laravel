<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    protected $chatRepository;

    public function __construct(ChatRepositoryInterface $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    /**
     * Start a new chat session
     */
    /**
     * Start a new chat session
     */
    public function startSession(Request $request): JsonResponse
    {
        $data = [];

        if (auth()->check()) {
            // User đã login - lưu user_id
            $data['user_id'] = auth()->id();
            $data['session_id'] = 'user-' . auth()->id() . '-' . uniqid();
        } else {
            // Guest user - validate và lưu thông tin guest
            $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email|max:255',
            ]);

            $data['guest_name'] = $request->guest_name;
            $data['guest_email'] = $request->guest_email;
            // Lưu session_id từ PHP session
            $data['session_id'] = 'guest-' . session()->getId() . '-' . uniqid();
        }

        try {
            $session = $this->chatRepository->getOrCreateSession($data);

            \Log::info('Chat session started', [
                'uuid' => $session->uuid,
                'session_id' => $session->session_id,
                'user_id' => $session->user_id ?? null,
                'guest_name' => $session->guest_name ?? null,
                'is_guest' => !auth()->check(),
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $session->uuid, // Return UUID
                'message' => 'Phiên chat đã được khởi tạo!',
            ]);
        } catch (\Exception $e) {
            \Log::error('Start session error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi khởi tạo phiên chat!',
                ],
                500,
            );
        }
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:1000',
        ]);

        try {
            // Find session first
            $session = $this->chatRepository->findByUuid($request->session_id);

            if (!$session) {
                \Log::warning('Session not found', ['session_id' => $request->session_id]);

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Phiên chat không tồn tại!',
                    ],
                    404,
                );
            }

            // Determine sender type: user or admin
            $senderType = auth()->check() && auth()->user()->hasRole('admin') ? 'admin' : 'user';
            $senderId = auth()->id() ?? null;

            \Log::info('Sending message', [
                'session_uuid' => $request->session_id,
                'session_id' => $session->id,
                'sender_type' => $senderType,
                'sender_id' => $senderId,
                'message' => substr($request->message, 0, 50) . '...',
            ]);

            $message = $this->chatRepository->sendMessage($request->session_id, [
                'sender_type' => $senderType,
                'sender_id' => $senderId,
                'message' => $request->message,
                'is_read' => $senderType === 'admin', // Admin messages are auto-read
            ]);

            // Load sender relationship
            $message->load('sender:id,fullname');

            \Log::info('Message sent successfully', [
                'message_id' => $message->id,
                'sender_type' => $message->sender_type,
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            \Log::error('Send message error', [
                'session_id' => $request->session_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi gửi tin nhắn: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Get messages for a session
     */
    public function getMessages(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            $session = $this->chatRepository->findByUuid($request->session_id);

            if (!$session) {
                return response()->json(
                    [
                        'success' => false,
                        'messages' => [],
                        'message' => 'Phiên chat không tồn tại!',
                    ],
                    404,
                );
            }

            $messages = $this->chatRepository->getSessionMessages($request->session_id, 100);

            \Log::info('Messages loaded', [
                'session_id' => $request->session_id,
                'count' => $messages->count(),
            ]);

            return response()->json([
                'success' => true,
                'messages' => $messages,
            ]);
        } catch (\Exception $e) {
            \Log::error('Get messages error', [
                'session_id' => $request->session_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(
                [
                    'success' => false,
                    'messages' => [],
                    'message' => 'Có lỗi xảy ra khi tải tin nhắn!',
                ],
                500,
            );
        }
    }

    /**
     * Poll for new messages (for chat widget)
     */
    public function pollMessages(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string',
            'last_message_id' => 'nullable|integer',
        ]);

        try {
            $sessionUuid = $request->session_id;
            $lastMessageId = $request->get('last_message_id', 0);

            // Find session by UUID
            $session = $this->chatRepository->findByUuid($sessionUuid);

            if (!$session) {
                \Log::warning('Poll: Session not found', [
                    'session_uuid' => $sessionUuid,
                ]);

                return response()->json(
                    [
                        'success' => false,
                        'messages' => [],
                        'message' => 'Phiên chat không tồn tại!',
                    ],
                    404,
                );
            }

            // Get only NEW admin messages (after lastMessageId)
            $messages = \App\Models\ChatMessage::where('session_id', $session->id)
                ->where('sender_type', 'admin') // Widget only polls ADMIN messages
                ->where('id', '>', $lastMessageId) // Only NEW messages
                ->with('sender:id,fullname')
                ->orderBy('created_at', 'asc')
                ->get();

            if ($messages->count() > 0) {
                \Log::info('New messages polled', [
                    'session_uuid' => $sessionUuid,
                    'count' => $messages->count(),
                    'last_message_id' => $lastMessageId,
                    'new_message_ids' => $messages->pluck('id')->toArray(),
                ]);
            }

            return response()->json([
                'success' => true,
                'messages' => $messages,
            ]);
        } catch (\Exception $e) {
            \Log::error('Poll messages error', [
                'session_id' => $request->session_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(
                [
                    'success' => false,
                    'messages' => [],
                    'message' => 'Có lỗi xảy ra khi kiểm tra tin nhắn mới!',
                ],
                500,
            );
        }
    }
}
