<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mention extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'mentioned_user_id',
        'mentioned_by',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    /**
     * Get the message that contains the mention.
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'message_id');
    }

    /**
     * Get the user who was mentioned.
     */
    public function mentionedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }

    /**
     * Get the user who made the mention.
     */
    public function mentionedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentioned_by');
    }

    /**
     * Mark the mention as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Get unread mentions count for a user.
     */
    public static function getUnreadCountForUser(User $user): int
    {
        return static::where('mentioned_user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get unread mentions for a user.
     */
    public static function getUnreadForUser(User $user)
    {
        return static::where('mentioned_user_id', $user->id)
            ->where('is_read', false)
            ->with(['message.room', 'message.user', 'mentionedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}