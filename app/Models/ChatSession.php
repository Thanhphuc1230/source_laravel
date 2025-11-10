<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    use HasFactory;

    protected $table = 'tp_chat_sessions';

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'session_id',
        'uuid',
        'status',
        'last_message_at'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the user that owns the chat session (for registered users)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all messages for this chat session
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'session_id');
    }

    /**
     * Get the latest message for this session
     */
    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'session_id')->latest();
    }

    /**
     * Get unread messages count for admin
     */
    public function getUnreadMessagesCountAttribute()
    {
        return $this->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get display name for the session
     */
    public function getDisplayNameAttribute()
    {
        if ($this->user) {
            return $this->user->fullname;
        }

        return $this->guest_name ?: 'Khách hàng';
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for sessions with unread messages
     */
    public function scopeWithUnreadMessages($query)
    {
        return $query->whereHas('messages', function ($q) {
            $q->where('sender_type', 'user')
              ->where('is_read', false);
        });
    }
}
