<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'parent_id',
        'message',
        'type',
        'metadata',
        'is_edited',
        'edited_at',
        'is_deleted',
        'deleted_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'is_deleted' => 'boolean',
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the chat room that owns the message.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }

    /**
     * Get the user who sent the message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent message (for threaded discussions).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'parent_id');
    }

    /**
     * Get the replies to this message.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'parent_id')
            ->where('is_deleted', false)
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get the mentions for this message.
     */
    public function mentions(): HasMany
    {
        return $this->hasMany(Mention::class, 'message_id');
    }

    /**
     * Get the mentioned users.
     */
    public function mentionedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'mentions', 'message_id', 'mentioned_user_id')
            ->withPivot(['mentioned_by', 'is_read', 'read_at'])
            ->withTimestamps();
    }

    /**
     * Check if message contains mentions.
     */
    public function hasMentions(): bool
    {
        return $this->mentions()->exists();
    }

    /**
     * Get formatted message with mentions highlighted.
     */
    public function getFormattedMessageAttribute(): string
    {
        $message = $this->message;
        
        // Replace @mentions with clickable links
        $message = preg_replace_callback('/@(\w+)/', function ($matches) {
            $username = $matches[1];
            $user = User::where('name', 'LIKE', "%{$username}%")->first();
            
            if ($user) {
                return "<span class='mention' data-user-id='{$user->id}'>@{$user->name}</span>";
            }
            
            return $matches[0];
        }, $message);

        return $message;
    }

    /**
     * Get file information if message contains a file.
     */
    public function getFileInfo(): ?array
    {
        if ($this->type === 'file' && $this->metadata) {
            return $this->metadata;
        }
        
        return null;
    }

    /**
     * Check if message is a reply.
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Get the thread depth (how many levels deep in replies).
     */
    public function getThreadDepth(): int
    {
        $depth = 0;
        $current = $this;
        
        while ($current->parent_id) {
            $depth++;
            $current = $current->parent;
        }
        
        return $depth;
    }
}