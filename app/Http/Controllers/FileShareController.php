<?php

namespace App\Http\Controllers;

use App\Models\FileShare;
use App\Models\ChatRoom;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileShareController extends Controller
{
    /**
     * Upload a file.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400', // 100MB max
            'room_id' => 'nullable|exists:chat_rooms,id',
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $user = auth()->user();

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/files', $filename, 'public');

        $fileShare = FileShare::create([
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $user->id,
            'room_id' => $request->room_id,
            'project_id' => $request->project_id,
            'task_id' => $request->task_id,
            'description' => $request->description,
            'is_public' => $request->is_public ?? false,
        ]);

        $fileShare->load('uploader');

        return response()->json($fileShare, 201);
    }

    /**
     * Get files for a specific context (room, project, task).
     */
    public function index(Request $request)
    {
        $query = FileShare::with('uploader');

        if ($request->room_id) {
            $room = ChatRoom::findOrFail($request->room_id);
            if (!$room->hasUser(auth()->user())) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $query->where('room_id', $request->room_id);
        }

        if ($request->project_id) {
            $project = Project::findOrFail($request->project_id);
            if (!$project->users()->where('user_id', auth()->id())->exists()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $query->where('project_id', $request->project_id);
        }

        if ($request->task_id) {
            $task = Task::findOrFail($request->task_id);
            if (!$task->assignedUsers()->where('user_id', auth()->id())->exists()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $query->where('task_id', $request->task_id);
        }

        $files = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($files);
    }

    /**
     * Download a file.
     */
    public function download(FileShare $fileShare)
    {
        if (!$fileShare->userCanAccess(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!Storage::disk('public')->exists($fileShare->file_path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::disk('public')->download(
            $fileShare->file_path,
            $fileShare->original_filename
        );
    }

    /**
     * Get file preview/info.
     */
    public function show(FileShare $fileShare)
    {
        if (!$fileShare->userCanAccess(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $fileShare->load(['uploader', 'room', 'project', 'task', 'versions']);

        return response()->json($fileShare);
    }

    /**
     * Update file metadata.
     */
    public function update(Request $request, FileShare $fileShare)
    {
        if ($fileShare->uploaded_by !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $fileShare->update($request->only(['description', 'is_public']));

        return response()->json($fileShare);
    }

    /**
     * Delete a file.
     */
    public function destroy(FileShare $fileShare)
    {
        if ($fileShare->uploaded_by !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the physical file
        if (Storage::disk('public')->exists($fileShare->file_path)) {
            Storage::disk('public')->delete($fileShare->file_path);
        }

        $fileShare->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }

    /**
     * Upload a new version of an existing file.
     */
    public function uploadVersion(Request $request, FileShare $fileShare)
    {
        if ($fileShare->uploaded_by !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $user = auth()->user();

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/files', $filename, 'public');

        $newVersion = FileShare::create([
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $user->id,
            'room_id' => $fileShare->room_id,
            'project_id' => $fileShare->project_id,
            'task_id' => $fileShare->task_id,
            'version' => $fileShare->version + 1,
            'parent_file_id' => $fileShare->parent_file_id ?: $fileShare->id,
            'description' => $request->description,
            'is_public' => $fileShare->is_public,
        ]);

        $newVersion->load('uploader');

        return response()->json($newVersion, 201);
    }

    /**
     * Get file versions.
     */
    public function getVersions(FileShare $fileShare)
    {
        if (!$fileShare->userCanAccess(auth()->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $parentId = $fileShare->parent_file_id ?: $fileShare->id;
        
        $versions = FileShare::where('parent_file_id', $parentId)
            ->orWhere('id', $parentId)
            ->with('uploader')
            ->orderBy('version', 'desc')
            ->get();

        return response()->json($versions);
    }

    /**
     * Get recent files for the authenticated user.
     */
    public function getRecent()
    {
        $user = auth()->user();
        
        $files = FileShare::where('uploaded_by', $user->id)
            ->orWhere('is_public', true)
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($files);
    }

    /**
     * Search files.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $user = auth()->user();

        $files = FileShare::where(function ($q) use ($query) {
                $q->where('original_filename', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->where(function ($q) use ($user) {
                $q->where('uploaded_by', $user->id)
                  ->orWhere('is_public', true);
            })
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($files);
    }
}