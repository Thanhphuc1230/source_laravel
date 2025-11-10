<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'tp_chat_messages';

    protected $fillable = [
        'session_id',
        'session_uuid',
        'sender_type',
        'sender_id',
        'message',
        'uuid',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Get the chat session that owns the message
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }

    /**
     * Get the sender (user or admin)
     */
    public function sender()
    {
        // Always return User relationship since both admin and users are in users table
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get sender display name
     */
    public function getSenderNameAttribute()
    {
        $sender = $this->sender;

        if ($sender) {
            return $sender->fullname;
        }

        return $this->sender_type === 'admin' ? 'Admin' : 'Khách hàng';
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for messages from users
     */
    public function scopeFromUsers($query)
    {
        return $query->where('sender_type', 'user');
    }

    /**
     * Scope for messages from admin
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('sender_type', 'admin');
    }
}
