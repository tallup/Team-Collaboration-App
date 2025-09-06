<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'project_id',
        'task_id',
        'created_by',
        'is_private',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_private' => 'boolean'
    ];

    /**
     * Get the project that owns the chat room.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the task that owns the chat room.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who created the chat room.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the users that belong to the chat room.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_room_user', 'room_id', 'user_id')
            ->withPivot(['role', 'joined_at', 'last_read_at', 'is_muted'])
            ->withTimestamps();
    }

    /**
     * Get the messages for the chat room.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'room_id')
            ->where('is_deleted', false)
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message for the chat room.
     */
    public function latestMessage(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'room_id')
            ->where('is_deleted', false)
            ->latest();
    }

    /**
     * Get the file shares for the chat room.
     */
    public function fileShares(): HasMany
    {
        return $this->hasMany(FileShare::class, 'room_id');
    }

    /**
     * Check if a user is a member of this room.
     */
    public function hasUser(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is an admin of this room.
     */
    public function isAdmin(User $user): bool
    {
        return $this->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    /**
     * Get unread message count for a user.
     */
    public function getUnreadCountForUser(User $user): int
    {
        $lastRead = $this->users()
            ->where('user_id', $user->id)
            ->first()
            ?->pivot
            ?->last_read_at;

        if (!$lastRead) {
            return $this->messages()->count();
        }

        return $this->messages()
            ->where('created_at', '>', $lastRead)
            ->count();
    }
}