<?php

namespace App\Repositories\Eloquent;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ChatRepository extends BaseRepository implements ChatRepositoryInterface
{
    /**
     * ChatRepository constructor.
     *
     * @param ChatSession $model
     */
    public function __construct(ChatSession $model)
    {
        parent::__construct($model);
    }

    public function getActiveSessions($perPage = 15)
    {
        return $this->model
            ->with(['user', 'latestMessage'])
            ->active()
            ->orderBy('last_message_at', 'desc')
            ->paginate($perPage);
    }

    // BỎ method findByUuid() đi - dùng của BaseRepository

    /**
     * @inheritDoc
     */
    public function getOrCreateSession(array $data)
    {
        Log::info('ChatRepository getOrCreateSession', [
            'data' => $data,
            'has_user_id' => isset($data['user_id']),
            'has_session_id' => isset($data['session_id']),
        ]);

        // For registered users
        if (isset($data['user_id']) && $data['user_id']) {
            $session = $this->model
                ->where('user_id', $data['user_id'])
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$session) {
                $session = $this->model->create([
                    'user_id' => $data['user_id'],
                    'session_id' => $data['session_id'] ?? 'user-' . $data['user_id'] . '-' . uniqid(),
                    'uuid' => Str::uuid(),
                    'status' => 'active',
                    'last_message_at' => now(),
                ]);

                Log::info('New user session created', [
                    'session_id' => $session->id,
                    'uuid' => $session->uuid,
                    'user_id' => $session->user_id,
                ]);
            }

            return $session;
        }

        // For guest users - check by session_id
        if (isset($data['session_id']) && $data['session_id']) {
            $session = $this->model
                ->where('session_id', $data['session_id'])
                ->where('status', 'active')
                ->latest()
                ->first();

            if (!$session) {
                $session = $this->model->create([
                    'user_id' => null,
                    'guest_name' => $data['guest_name'] ?? null,
                    'guest_email' => $data['guest_email'] ?? null,
                    'session_id' => $data['session_id'],
                    'uuid' => Str::uuid(),
                    'status' => 'active',
                    'last_message_at' => now(),
                ]);

                Log::info('New guest session created', [
                    'session_id' => $session->id,
                    'uuid' => $session->uuid,
                    'guest_name' => $session->guest_name,
                    'guest_email' => $session->guest_email,
                ]);
            }

            return $session;
        }

        // Create new guest session without session_id (fallback)
        $session = $this->model->create([
            'user_id' => null,
            'guest_name' => $data['guest_name'] ?? null,
            'guest_email' => $data['guest_email'] ?? null,
            'session_id' => 'guest-' . uniqid(),
            'uuid' => Str::uuid(),
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        Log::info('New guest session created (fallback)', [
            'session_id' => $session->id,
            'uuid' => $session->uuid,
        ]);

        return $session;
    }

    /**
     * @inheritDoc
     */
    public function sendMessage($sessionUuid, array $messageData)
    {
        // Sử dụng findByUuid từ BaseRepository
        $session = $this->findByUuid($sessionUuid);

        if (!$session) {
            Log::error('ChatRepository sendMessage: Session not found', [
                'session_uuid' => $sessionUuid,
                'message_data' => $messageData,
            ]);
            throw new \Exception('Chat session not found');
        }

        Log::info('ChatRepository sendMessage: Session found', [
            'session_uuid' => $sessionUuid,
            'session_id' => $session->id,
            'session_user_id' => $session->user_id,
            'is_guest' => $session->user_id === null,
            'sender_type' => $messageData['sender_type'],
            'sender_id' => $messageData['sender_id'] ?? 'NULL',
        ]);

        // Create message - sender_id có thể NULL cho guest
        $message = ChatMessage::create([
            'session_id' => $session->id,
            'session_uuid' => $session->uuid,
            'sender_type' => $messageData['sender_type'],
            'sender_id' => $messageData['sender_id'] ?? null,
            'message' => $messageData['message'],
            'uuid' => Str::uuid(),
            'is_read' => $messageData['is_read'] ?? false,
        ]);

        // Update session's last_message_at
        $session->update(['last_message_at' => now()]);

        Log::info('Message created successfully', [
            'message_id' => $message->id,
            'message_uuid' => $message->uuid,
            'sender_id' => $message->sender_id,
        ]);

        return $message->load('session');
    }

    /**
     * @inheritDoc
     */
    public function markMessagesAsRead($sessionUuid, $senderType = 'user')
    {
        $session = $this->findByUuid($sessionUuid);

        if (!$session) {
            return 0;
        }

        return ChatMessage::where('session_id', $session->id)
            ->where('sender_type', $senderType)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * @inheritDoc
     */
    public function getUnreadMessagesCount()
    {
        return ChatMessage::where('sender_type', 'user')
            ->where('is_read', false)
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function closeSession($sessionUuid)
    {
        $session = $this->findByUuid($sessionUuid);

        if (!$session) {
            return false;
        }

        return $session->update(['status' => 'closed']);
    }

    /**
     * Get messages for a session
     *
     * @param string $sessionUuid
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSessionMessages($sessionUuid, $limit = 50)
    {
        $session = $this->findByUuid($sessionUuid);

        if (!$session) {
            return collect();
        }

        return ChatMessage::where('session_id', $session->id)
            ->with('sender:id,fullname')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get sessions with unread messages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSessionsWithUnreadMessages()
    {
        return $this->model
            ->with(['user', 'latestMessage'])
            ->withUnreadMessages()
            ->active()
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    /**
     * Find session by session_id (UUID) or id (for backward compatibility)
     *
     * @param string|int $sessionId
     * @return \App\Models\ChatSession|null
     */
    public function findBySessionId($sessionId)
    {
        // If it's a UUID, find by session_id
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $sessionId)) {
            return $this->model->where('session_id', $sessionId)->first();
        }

        // If it's numeric, find by id
        if (is_numeric($sessionId)) {
            return $this->find($sessionId);
        }

        // Otherwise, find by session_id string (for legacy sessions)
        return $this->model->where('session_id', $sessionId)->first();
    }

    /**
     * Create a new session
     *
     * @param array $data
     * @return \App\Models\ChatSession
     */
    public function createSession(array $data)
    {
        return $this->model->create([
            'user_id' => $data['user_id'] ?? null,
            'guest_name' => $data['guest_name'] ?? null,
            'guest_email' => $data['guest_email'] ?? null,
            'session_id' => $data['session_id'] ?? Str::uuid(),
            'uuid' => Str::uuid(),
            'status' => 'active',
            'last_message_at' => now(),
        ]);
    }
}