<?php

namespace App\Repositories\Interfaces;

interface ChatRepositoryInterface extends RepositoryInterface
{
    /**
     * Get active chat sessions
     *
     * @param int $perPage
     * @return mixed
     */
    public function getActiveSessions($perPage = 15);

    /**
     * Get or create a chat session
     *
     * @param array $data
     * @return \App\Models\ChatSession
     */
    public function getOrCreateSession(array $data);

    /**
     * Send a message in a session
     *
     * @param string $sessionUuid
     * @param array $messageData
     * @return \App\Models\ChatMessage
     */
    public function sendMessage($sessionUuid, array $messageData);

    /**
     * Mark messages as read
     *
     * @param string $sessionUuid
     * @param string $senderType
     * @return int
     */
    public function markMessagesAsRead($sessionUuid, $senderType = 'user');

    /**
     * Get unread messages count
     *
     * @return int
     */
    public function getUnreadMessagesCount();

    /**
     * Close a chat session
     *
     * @param string $sessionUuid
     * @return bool
     */
    public function closeSession($sessionUuid);

    /**
     * Get messages for a session
     *
     * @param string $sessionUuid
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSessionMessages($sessionUuid, $limit = 50);

    /**
     * Get sessions with unread messages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSessionsWithUnreadMessages();

    /**
     * Find session by session_id
     *
     * @param string|int $sessionId
     * @return \App\Models\ChatSession|null
     */
    public function findBySessionId($sessionId);
}