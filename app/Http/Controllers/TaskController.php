<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Update task status via AJAX.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,completed'
        ]);

        $oldStatus = $task->status;
        $currentUser = auth()->user();

        $task->update([
            'status' => $request->status
        ]);

        // Send email notifications to assigned users about status change
        $assignedUsers = $task->assignedUsers;
        foreach ($assignedUsers as $user) {
            // Don't notify the user who made the change
            if ($user->id !== $currentUser->id) {
                $user->notify(new \App\Notifications\TaskStatusChanged($task, $oldStatus, $currentUser));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully'
        ]);
    }
}