<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_by',
        'room_id',
        'project_id',
        'task_id',
        'version',
        'parent_file_id',
        'description',
        'is_public',
        'permissions'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_public' => 'boolean'
    ];

    /**
     * Get the user who uploaded the file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the chat room that owns the file.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }

    /**
     * Get the project that owns the file.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the task that owns the file.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the parent file (for version control).
     */
    public function parentFile(): BelongsTo
    {
        return $this->belongsTo(FileShare::class, 'parent_file_id');
    }

    /**
     * Get the versions of this file.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(FileShare::class, 'parent_file_id')
            ->orderBy('version', 'desc');
    }

    /**
     * Get the latest version of this file.
     */
    public function latestVersion(): HasMany
    {
        return $this->hasMany(FileShare::class, 'parent_file_id')
            ->orderBy('version', 'desc')
            ->limit(1);
    }

    /**
     * Get human readable file size.
     */
    public function getHumanFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file extension.
     */
    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->original_filename, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is an image.
     */
    public function isImage(): bool
    {
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        return in_array(strtolower($this->file_extension), $imageTypes);
    }

    /**
     * Check if file is a document.
     */
    public function isDocument(): bool
    {
        $documentTypes = ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt'];
        return in_array(strtolower($this->file_extension), $documentTypes);
    }

    /**
     * Check if user has permission to access this file.
     */
    public function userCanAccess(User $user): bool
    {
        // Public files can be accessed by anyone
        if ($this->is_public) {
            return true;
        }

        // Owner can always access
        if ($this->uploaded_by === $user->id) {
            return true;
        }

        // Check specific permissions
        if ($this->permissions) {
            return in_array($user->id, $this->permissions);
        }

        // Check room membership
        if ($this->room_id) {
            return $this->room->hasUser($user);
        }

        // Check project membership
        if ($this->project_id) {
            return $this->project->users()->where('user_id', $user->id)->exists();
        }

        // Check task assignment
        if ($this->task_id) {
            return $this->task->assignedUsers()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Get the full URL to the file.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}