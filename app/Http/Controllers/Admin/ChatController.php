<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;

class ChatController extends BaseController
{
    protected $module;
    protected $model;
    protected $nameItem;
    protected $chatRepository;

    public function __construct(ChatRepositoryInterface $chatRepository)
    {
        $this->module = 'chat';
        $this->model = new ChatSession();
        $this->nameItem = 'chat';
        $this->chatRepository = $chatRepository;

        parent::__construct($this->module);

        View::share('nameClass', $this->module);
    }

    /**
     * Display chat dashboard
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status', 'all');

        $query = $this->model->with(['user', 'latestMessage.sender']);

        // Filter by status
        if ($status === 'active') {
            $query->where('status', 'active');
        } elseif ($status === 'closed') {
            $query->where('status', 'closed');
        }

        $sessions = $query->orderBy('last_message_at', 'desc')->orderBy('created_at', 'desc')->paginate($perPage);

        $unreadCount = $this->chatRepository->getUnreadMessagesCount();

        // If AJAX request, return JSON
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'sessions' => $sessions->map(function ($session) {
                    return [
                        'uuid' => $session->uuid,
                        'user' => $session->user
                            ? [
                                'fullname' => $session->user->fullname,
                                'email' => $session->user->email,
                            ]
                            : null,
                        'guest_name' => $session->guest_name,
                        'guest_email' => $session->guest_email,
                        'status' => $session->status,
                        'latest_message' => $session->latestMessage
                            ? [
                                'message' => $session->latestMessage->message,
                                'sender_type' => $session->latestMessage->sender_type,
                                'is_read' => $session->latestMessage->is_read,
                            ]
                            : null,
                        'last_message_at_human' => $session->last_message_at ? $session->last_message_at->diffForHumans() : $session->created_at->diffForHumans(),
                    ];
                }),
                'total' => $sessions->total(),
                'unread_count' => $unreadCount,
            ]);
        }

        $data['sessions'] = $sessions;
        $data['unreadCount'] = $unreadCount;
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('index', $data);
    }

    /**
     * Show chat session with messages
     */
    public function show($uuid)
    {
        // Find session by UUID
        $session = $this->chatRepository->findByUuid($uuid);
        if (!$session) {
            toast('Phiên chat không tồn tại!', 'error');
            return redirect()->route('admin.chat.index');
        }

        // Mark user messages as read
        $this->chatRepository->markMessagesAsRead($session->uuid, 'user');

        $data['session'] = $session->load([
            'messages' => function ($query) {
                $query->with(['sender:id,fullname'])->orderBy('created_at', 'asc');
            },
        ]);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('show', $data);
    }

    /**
     * Send message from admin
     */
    public function sendMessage(Request $request, $uuid): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $session = $this->chatRepository->findByUuid($uuid);
            if (!$session) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Phiên chat không tồn tại!',
                    ],
                    404,
                );
            }

            $message = $this->chatRepository->sendMessage($session->uuid, [
                'sender_type' => 'admin',
                'sender_id' => auth()->id(),
                'message' => $request->message,
                'is_read' => true,
            ]);

            // Load sender relationship for response
            $message->load('sender:id,fullname');

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi gửi tin nhắn!',
                ],
                500,
            );
        }
    }

    /**
     * Get new messages for a session
     */
    public function getNewMessages(Request $request, $uuid): JsonResponse
    {
        $lastMessageId = $request->get('last_message_id', 0);

        $session = $this->chatRepository->findByUuid($uuid);
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Get only NEW messages after lastMessageId
        $messages = ChatMessage::where('session_id', $session->id)
            ->where('id', '>', $lastMessageId)
            ->where('sender_type', 'user') // Admin only polls USER messages
            ->with('sender:id,fullname')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark user messages as read
        if ($messages->count() > 0) {
            $this->chatRepository->markMessagesAsRead($session->uuid, 'user');
        }

        return response()->json([
            'messages' => $messages,
            'unread_count' => $this->chatRepository->getUnreadMessagesCount(),
        ]);
    }

    public function closeSession($uuid)
    {
        $session = $this->chatRepository->findByUuid($uuid);
        if (!$session) {
            toast('Phiên chat không tồn tại!', 'error');
            return redirect()->route('admin.chat.index');
        }

        try {
            $this->chatRepository->closeSession($session->uuid);
            toast('Đã đóng phiên chat!', 'success');
        } catch (\Exception $e) {
            toast('Có lỗi xảy ra khi đóng phiên chat!', 'error');
        }

        return redirect()->route('admin.chat.index');
    }

    /**
     * Get unread messages count (for header notification)
     */
    public function getUnreadCount(): JsonResponse
    {
        return response()->json([
            'unread_count' => $this->chatRepository->getUnreadMessagesCount(),
        ]);
    }

    /**
     * Bulk close sessions
     */
    public function bulkClose(Request $request)
    {
        $sessionUuids = $request->input('session_uuids', []);

        if (empty($sessionUuids)) {
            toast('Vui lòng chọn phiên chat cần đóng!', 'error');
            return redirect()->back();
        }

        try {
            foreach ($sessionUuids as $uuid) {
                $session = $this->chatRepository->findByUuid($uuid);
                if ($session) {
                    $this->chatRepository->closeSession($session->uuid);
                }
            }

            toast('Đã đóng ' . count($sessionUuids) . ' phiên chat!', 'success');
        } catch (\Exception $e) {
            toast('Có lỗi xảy ra khi đóng phiên chat!', 'error');
        }

        return redirect()->route('admin.chat.index');
    }
}
