<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect('/admin/custom-dashboard');
});

// Custom authentication routes
Route::middleware('guest')->group(function () {
    // Custom login page
    Route::get('/custom-login', function () {
        return view('custom-login');
    })->name('login');
    
    // Custom register page (if needed)
    Route::get('/custom-register', function () {
        return view('custom-register');
    })->name('register');
    
    // Handle login form submission
    Route::post('/custom-login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    
    // Handle register form submission (if needed)
    Route::post('/custom-register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

// Route::get('/admin', function () {
//     return redirect('/admin/custom-dashboard');
// });

// Enhanced dashboard route
Route::get('/admin/dashboard-enhanced', function () {
    return view('filament.admin.pages.dashboard-enhanced');
})->middleware('auth');

// Test email notification route
Route::get('/test-email', function () {
    $user = \App\Models\User::first();
    if ($user) {
        $user->notify(new \App\Notifications\TaskAssigned(
            \App\Models\Task::first() ?? new \App\Models\Task(['title' => 'Test Task', 'description' => 'This is a test task', 'status' => 'todo', 'priority' => 'medium']),
            auth()->user()
        ));
        return 'Test email sent to ' . $user->name . ' (' . $user->email . ')';
    }
    return 'No users found';
})->middleware('auth');

Route::get('/admin/custom-dashboard', function () {
    return view('custom-dashboard');
})->middleware('auth');

// Chat page route
Route::get('/admin/chat', function () {
    return view('custom-chat');
})->middleware('auth');

// Video call page route
Route::get('/admin/video-call', function () {
    return view('custom-video-call');
})->middleware('auth');

Route::get('/admin/custom-projects', function () {
    $projects = \App\Models\Project::with(['primaryUser:id,name', 'owner:id,name', 'users:id,name', 'tasks'])
        ->select(['id', 'name', 'description', 'status', 'user_id', 'owner_id', 'created_at'])
        ->latest()
        ->get();
    
    return view('custom-projects', compact('projects'));
})->middleware('auth');

Route::get('/admin/custom-projects/create', function () {
    return view('custom-create-project');
})->middleware('auth');

Route::get('/admin/custom-tasks', function () {
    $tasks = \App\Models\Task::with(['project:id,name', 'assignedUsers:id,name', 'createdBy:id,name'])
        ->select(['id', 'title', 'description', 'status', 'priority', 'project_id', 'created_by', 'due_date', 'created_at'])
        ->latest()
        ->get();
    
    return view('custom-tasks', compact('tasks'));
})->middleware('auth');

Route::get('/admin/custom-tasks/board', function () {
    $tasks = \App\Models\Task::with(['project:id,name', 'assignedUsers:id,name', 'createdBy:id,name'])
        ->select(['id', 'title', 'description', 'status', 'priority', 'project_id', 'created_by', 'due_date', 'created_at'])
        ->latest()
        ->get();
    
    return view('custom-tasks-board', compact('tasks'));
})->middleware('auth');

// Task status update route
Route::post('/admin/custom-tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->middleware('auth');

// Comment routes
Route::post('/admin/custom-tasks/{task}/comments', function ($task) {
    try {
        $task = \App\Models\Task::findOrFail($task);
        request()->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment = \App\Models\Comment::create([
            'content' => request('content'),
            'task_id' => $task->id,
            'user_id' => auth()->id(),
        ]);
        
        // Send email notifications to assigned users about new comment
        $currentUser = auth()->user();
        $assignedUsers = $task->assignedUsers;
        foreach ($assignedUsers as $user) {
            // Don't notify the user who made the comment
            if ($user->id !== $currentUser->id) {
                $user->notify(new \App\Notifications\TaskCommentAdded($task, $comment, $currentUser));
            }
        }
        
        return response()->json([
            'message' => 'Comment added successfully!',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->format('M j, Y g:i A'),
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to add comment: ' . $e->getMessage()
        ], 500);
    }
})->middleware('auth');

Route::get('/admin/custom-tasks/{task}/comments', function ($task) {
    $task = \App\Models\Task::findOrFail($task);
    $comments = $task->comments()->with('user')->oldest()->get();
    
    return response()->json($comments->map(function ($comment) {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'user_name' => $comment->user->name,
            'created_at' => $comment->created_at->format('M j, Y g:i A'),
        ];
    }));
})->middleware('auth');

Route::get('/admin/custom-tasks/create', function () {
    return view('custom-create-task');
})->middleware('auth');

Route::get('/admin/custom-tasks/{task}/edit', function ($task) {
    $task = \App\Models\Task::with(['assignedUsers', 'project', 'createdBy'])->findOrFail($task);
    return view('custom-edit-task', compact('task'));
})->middleware('auth');

Route::get('/admin/custom-users', function () {
    $users = \App\Models\User::all();
    return view('custom-users', compact('users'));
})->middleware('auth');

Route::get('/admin/custom-users/create', function () {
    return view('custom-create-user');
})->middleware('auth');

Route::post('/admin/users', function () {
    request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'position' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'is_admin' => 'boolean',
    ]);

    \App\Models\User::create([
        'name' => request('name'),
        'email' => request('email'),
        'password' => bcrypt(request('password')),
        'position' => request('position'),
        'phone' => request('phone'),
        'is_admin' => request('is_admin', false),
    ]);

    return redirect('/admin/custom-users')->with('success', 'User created successfully!');
})->middleware('auth');

Route::get('/admin/custom-users/{user}/edit', function ($user) {
    $user = \App\Models\User::findOrFail($user);
    return view('custom-edit-user', compact('user'));
})->middleware('auth');

Route::put('/admin/users/{user}', function ($user) {
    $user = \App\Models\User::findOrFail($user);
    
    // Validate the request
    request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'position' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'is_admin' => 'boolean',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    // Update the user
    $user->update([
        'name' => request('name'),
        'email' => request('email'),
        'position' => request('position'),
        'phone' => request('phone'),
        'is_admin' => request('is_admin', false),
    ]);
    
    // Handle profile picture upload
    if (request()->hasFile('profile_picture')) {
        $file = request()->file('profile_picture');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/profiles'), $filename);
        $user->update(['profile_picture' => 'uploads/profiles/' . $filename]);
    }
    
    return redirect('/admin/custom-users')->with('success', 'User updated successfully!');
})->middleware('auth');

// Debug route for project creation
Route::get('/debug-project', function () {
    try {
        $user = \App\Models\User::first();
        if (!$user) {
            return 'No users found';
        }
        
        // Try to create project with minimal data first - disable foreign key checks
        \DB::statement('PRAGMA foreign_keys=OFF;');
        
        $project = \App\Models\Project::create([
            'name' => 'Test Project ' . time(),
            'status' => 'planning',
            'owner_id' => $user->id,
            'user_id' => $user->id,
        ]);
        
        \DB::statement('PRAGMA foreign_keys=ON;');
        
        return 'Project created successfully with ID: ' . $project->id;
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>Line: ' . $e->getLine() . '<br>File: ' . $e->getFile();
    }
})->middleware('auth');

// Debug route for project relationships
Route::get('/debug-project-users', function () {
    $projects = \App\Models\Project::with(['users:id,name', 'owner:id,name'])->get();
    $output = '<h2>Project-User Relationships Debug</h2>';
    
    foreach ($projects as $project) {
        $output .= '<h3>Project: ' . $project->name . '</h3>';
        $output .= '<p>Owner: ' . ($project->owner ? $project->owner->name : 'No owner') . '</p>';
        $output .= '<p>Users count: ' . $project->users->count() . '</p>';
        $output .= '<ul>';
        foreach ($project->users as $user) {
            $output .= '<li>' . $user->name . ' (ID: ' . $user->id . ')</li>';
        }
        $output .= '</ul><hr>';
    }
    
    return $output;
})->middleware('auth');

// Debug route to add test profile pictures
Route::get('/debug-add-profile-pics', function () {
    $users = \App\Models\User::all();
    $output = '<h2>Adding Test Profile Pictures</h2>';
    
    foreach ($users as $user) {
        // Create a simple colored avatar based on user ID
        $colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe'];
        $color = $colors[$user->id % count($colors)];
        
        // For now, just show what would be done
        $output .= '<p>User: ' . $user->name . ' - Would get color: ' . $color . '</p>';
    }
    
    $output .= '<p><strong>Note:</strong> This is just a debug route. In a real scenario, you would upload actual profile pictures through the edit form.</p>';
    
    return $output;
})->middleware('auth');

Route::post('/admin/custom-projects', function () {
    try {
        // Validate the request
        request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,active,on_hold,completed',
            'assigned_users' => 'nullable',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        // Get the current authenticated user
        $currentUser = auth()->user();
        if (!$currentUser) {
            return redirect()->back()->with('error', 'You must be logged in to create a project.');
        }
        
        // Create the project - temporarily disable foreign key checks
        \DB::statement('PRAGMA foreign_keys=OFF;');
        
        $project = \App\Models\Project::create([
            'name' => request('name'),
            'description' => request('description'),
            'status' => request('status'),
            'owner_id' => $currentUser->id,
            'user_id' => $currentUser->id,
            'start_date' => request('start_date'),
            'due_date' => request('due_date'),
        ]);
        
        \DB::statement('PRAGMA foreign_keys=ON;');
    
        // Handle assigned users if provided
        $userIds = [];
        if (request('assigned_users')) {
            // Handle both array (from checkboxes) and string (from old dropdown) formats
            if (is_array(request('assigned_users'))) {
                $userIds = request('assigned_users');
            } else {
                $userIds = explode(',', request('assigned_users'));
            }
        }
        
        // Always include the current user (creator) in the project
        if (!in_array($currentUser->id, $userIds)) {
            $userIds[] = $currentUser->id;
        }
        
        // Attach all users to the project
        if (!empty($userIds)) {
            \DB::statement('PRAGMA foreign_keys=OFF;');
            $project->users()->attach($userIds, ['role' => 'member']);
            
            // Set the creator as owner
            $project->users()->updateExistingPivot($currentUser->id, ['role' => 'owner']);
            \DB::statement('PRAGMA foreign_keys=ON;');
            
            // Send email notifications to assigned users
            $assignedUsers = \App\Models\User::whereIn('id', $userIds)->get();
            foreach ($assignedUsers as $user) {
                // Don't notify the creator
                if ($user->id !== $currentUser->id) {
                    $user->notify(new \App\Notifications\ProjectAssigned($project, $currentUser));
                }
            }
        }
        
        // Redirect back to projects page with success message
        return redirect('/admin/custom-projects')->with('success', 'Project created successfully!');
        
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Project creation failed: ' . $e->getMessage());
        
        // Redirect back with error message
        return redirect()->back()->with('error', 'Failed to create project: ' . $e->getMessage())->withInput();
    }
})->middleware('auth');

Route::post('/admin/custom-tasks', function () {
    // Get the current authenticated user
    $currentUser = auth()->user();
    if (!$currentUser) {
        return redirect()->back()->with('error', 'You must be logged in to create a task.');
    }
    
    // Validate the request
    request()->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:todo,in_progress,review,completed',
        'priority' => 'required|in:low,medium,high,urgent',
        'project_id' => 'nullable|exists:projects,id',
        'assigned_users' => 'nullable',
        'due_date' => 'nullable|date',
    ]);
    
    // Create the task
    $task = \App\Models\Task::create([
        'title' => request('title'),
        'description' => request('description'),
        'status' => request('status'),
        'priority' => request('priority'),
        'project_id' => request('project_id'),
        'created_by' => $currentUser->id,
        'due_date' => request('due_date'),
    ]);
    
    // Handle assigned users if provided
    if (request('assigned_users')) {
        // Handle both array (from checkboxes) and string (from old dropdown) formats
        if (is_array(request('assigned_users'))) {
            $userIds = request('assigned_users');
        } else {
            $userIds = explode(',', request('assigned_users'));
        }
        $task->assignedUsers()->attach($userIds);
        
        // Send email notifications to assigned users
        $assignedUsers = \App\Models\User::whereIn('id', $userIds)->get();
        foreach ($assignedUsers as $user) {
            $user->notify(new \App\Notifications\TaskAssigned($task, $currentUser));
        }
    }
    
    // Redirect back to tasks page with success message
    return redirect('/admin/custom-tasks')->with('success', 'Task created successfully!');
})->middleware('auth');

Route::put('/admin/custom-tasks/{task}', function ($task) {
    $task = \App\Models\Task::findOrFail($task);
    
    // Validate the request
    request()->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:todo,in_progress,review,completed',
        'priority' => 'required|in:low,medium,high,urgent',
        'project_id' => 'nullable|exists:projects,id',
        'created_by' => 'required|exists:users,id',
        'assigned_users' => 'nullable|string',
        'due_date' => 'nullable|date',
        'estimated_hours' => 'nullable|numeric|min:0',
        'actual_hours' => 'nullable|numeric|min:0',
        'assigned_to' => 'nullable|exists:users,id',
    ]);
    
    // Update the task
    $task->update([
        'title' => request('title'),
        'description' => request('description'),
        'status' => request('status'),
        'priority' => request('priority'),
        'project_id' => request('project_id'),
        'created_by' => request('created_by'),
        'due_date' => request('due_date'),
        'estimated_hours' => request('estimated_hours'),
        'actual_hours' => request('actual_hours'),
        'assigned_to' => request('assigned_to'),
    ]);
    
    // Handle assigned users if provided
    if (request('assigned_users')) {
        // Handle both array (from checkboxes) and string (from old dropdown) formats
        if (is_array(request('assigned_users'))) {
            $userIds = request('assigned_users');
        } else {
            $userIds = explode(',', request('assigned_users'));
        }
        $task->assignedUsers()->sync($userIds);
    } else {
        $task->assignedUsers()->detach();
    }
    
    // Redirect back to tasks page with success message
    return redirect('/admin/custom-tasks')->with('success', 'Task updated successfully!');
})->middleware('auth');

// Project Comments Routes
Route::post('/admin/custom-projects/{project}/comments', function ($project) {
    try {
        $project = \App\Models\Project::findOrFail($project);
        request()->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment = \App\Models\Comment::create([
            'content' => request('content'),
            'project_id' => $project->id,
            'user_id' => auth()->id(),
        ]);
        
        return response()->json([
            'message' => 'Comment added successfully!',
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->format('M j, Y g:i A'),
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to add comment: ' . $e->getMessage()
        ], 500);
    }
})->middleware('auth');

Route::get('/admin/custom-projects/{project}/comments', function ($project) {
    $project = \App\Models\Project::findOrFail($project);
    $comments = $project->comments()->with('user')->oldest()->get();
    
    return response()->json($comments->map(function ($comment) {
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'user_name' => $comment->user->name,
            'created_at' => $comment->created_at->format('M j, Y g:i A'),
        ];
    }));
})->middleware('auth');



// Profile Routes
Route::get('/admin/profile', function () {
    return view('custom-profile');
})->middleware('auth');

Route::get('/admin/profile/edit', function () {
    return view('custom-profile-edit');
})->middleware('auth');

Route::put('/admin/profile/update', function () {
    $user = auth()->user();
    
    request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'position' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    $updateData = [
        'name' => request('name'),
        'email' => request('email'),
        'position' => request('position'),
        'phone' => request('phone'),
    ];
    
    // Handle profile picture upload
    if (request()->hasFile('profile_picture')) {
        $file = request()->file('profile_picture');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/profiles'), $filename);
        $updateData['profile_picture'] = 'uploads/profiles/' . $filename;
    }
    
    $user->update($updateData);
    
    return redirect('/admin/profile')->with('success', 'Profile updated successfully!');
})->middleware('auth');

Route::post('/admin/profile/password', function () {
    $user = auth()->user();
    
    request()->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);
    
    // Check if current password is correct
    if (!\Hash::check(request('current_password'), $user->password)) {
        return redirect()->back()->with('password_error', 'Current password is incorrect.');
    }
    
    // Update password
    $user->update([
        'password' => bcrypt(request('new_password')),
    ]);
    
    return redirect('/admin/profile/edit')->with('password_success', 'Password changed successfully!');
})->middleware('auth');

// Test route to check user data
Route::get('/test-user', function () {
    $user = auth()->user();
    if ($user) {
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture,
                'position' => $user->position,
                'phone' => $user->phone,
            ]
        ]);
    } else {
        return response()->json([
            'authenticated' => false,
            'message' => 'No authenticated user'
        ]);
    }
})->middleware('auth');

// Test route to create and login a user
Route::get('/test-login', function () {
    // Create or find a test user
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'test@usgamneeds.com'],
        [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'position' => 'Developer',
            'phone' => '+1234567890'
        ]
    );
    
    // Login the user
    auth()->login($user);
    
    return redirect('/admin/profile')->with('success', 'Test user logged in successfully!');
});

// Test route to assign multiple users to a task
Route::get('/test-multiple-users', function () {
    $task = \App\Models\Task::first();
    if (!$task) {
        return 'No tasks found';
    }
    
    // Get all users
    $users = \App\Models\User::all();
    
    // Assign all users to the first task
    $userIds = $users->pluck('id')->toArray();
    $task->assignedUsers()->sync($userIds);
    
    return "Assigned " . count($userIds) . " users to task: " . $task->title;
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/admin/custom-dashboard');
    })->name('dashboard');
});

// Chat and Communication Routes
Route::middleware(['auth'])->prefix('api/chat')->group(function () {
    Route::get('/rooms', [App\Http\Controllers\ChatController::class, 'index']);
    Route::post('/rooms', [App\Http\Controllers\ChatController::class, 'store']);
    Route::get('/rooms/{room}', [App\Http\Controllers\ChatController::class, 'show']);
    Route::post('/rooms/{room}/messages', [App\Http\Controllers\ChatController::class, 'sendMessage']);
    Route::get('/rooms/{room}/messages', [App\Http\Controllers\ChatController::class, 'getMessages']);
    Route::post('/rooms/{room}/users', [App\Http\Controllers\ChatController::class, 'addUsers']);
    Route::delete('/rooms/{room}/users', [App\Http\Controllers\ChatController::class, 'removeUsers']);
    Route::post('/rooms/{room}/leave', [App\Http\Controllers\ChatController::class, 'leave']);
    Route::get('/mentions/unread', [App\Http\Controllers\ChatController::class, 'getUnreadMentions']);
    Route::post('/mentions/read', [App\Http\Controllers\ChatController::class, 'markMentionsAsRead']);
    Route::get('/mentions/suggestions', [App\Http\Controllers\ChatController::class, 'getMentionSuggestions']);
});

// File Sharing Routes
Route::middleware(['auth'])->prefix('api/files')->group(function () {
    Route::post('/upload', [App\Http\Controllers\FileShareController::class, 'upload']);
    Route::get('/', [App\Http\Controllers\FileShareController::class, 'index']);
    Route::get('/recent', [App\Http\Controllers\FileShareController::class, 'getRecent']);
    Route::get('/search', [App\Http\Controllers\FileShareController::class, 'search']);
    Route::get('/{fileShare}', [App\Http\Controllers\FileShareController::class, 'show']);
    Route::get('/{fileShare}/download', [App\Http\Controllers\FileShareController::class, 'download']);
    Route::put('/{fileShare}', [App\Http\Controllers\FileShareController::class, 'update']);
    Route::delete('/{fileShare}', [App\Http\Controllers\FileShareController::class, 'destroy']);
    Route::post('/{fileShare}/version', [App\Http\Controllers\FileShareController::class, 'uploadVersion']);
    Route::get('/{fileShare}/versions', [App\Http\Controllers\FileShareController::class, 'getVersions']);
});

require __DIR__.'/auth.php';
