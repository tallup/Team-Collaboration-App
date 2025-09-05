<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - USGamNeeds Team Collaboration</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1f2937;
        }
        
        /* Horizontal Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .navbar-logo {
            width: 40px;
            height: 40px;
            background-image: url('/images/logo.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 8px;
        }
        
        .navbar-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }
        
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #6b7280;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .nav-item:hover {
            background-color: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        
        .nav-item.active {
            background-color: rgba(102, 126, 234, 0.15);
            color: #667eea;
        }
        
        .nav-item svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }
        
        /* Main Content */
        .main-content {
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 32px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .page-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        /* Form Container */
        .form-container {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
        }
        
        /* Two Column Layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }
        
        .form-row-single {
            grid-column: 1 / -1;
        }
        
        .form-row-full {
            grid-column: 1 / -1;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
            cursor: pointer;
        }
        
        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-date {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
        }
        
        .form-date:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-number {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
        }
        
        .form-number:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        /* Multi-select for users */
        .user-select-container {
            position: relative;
        }
        
        .user-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
            cursor: pointer;
            min-height: 48px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        
        .user-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .user-tag {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .user-tag-remove {
            cursor: pointer;
            font-weight: bold;
        }
        
        .user-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
            display: none;
        }
        
        .user-option {
            padding: 12px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .user-option:hover {
            background: #f8fafc;
        }
        
        .user-option.selected {
            background: #667eea;
            color: white;
        }
        
        /* Form Sections */
        .form-section {
            margin-bottom: 32px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f3f4f6;
        }
        
        /* Action Buttons */
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #f3f4f6;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
            color: #374151;
        }
        
        .btn-outline {
            background: transparent;
            color: #6b7280;
            border: 2px solid #e5e7eb;
        }
        
        .btn-outline:hover {
            background: #f8fafc;
            border-color: #d1d5db;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 0 16px;
            }
            
            .navbar-title {
                display: none;
            }
            
            .nav-item span {
                display: none;
            }
            
            .nav-item {
                padding: 8px;
            }
            
            .main-content {
                padding: 16px;
            }
            
            .page-header {
                padding: 24px;
            }
            
            .page-title {
                font-size: 24px;
            }
            
            .form-container {
                padding: 24px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .form-container {
                padding: 16px;
            }
            
            .page-header {
                padding: 20px;
            }
            
            .page-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Horizontal Navigation -->
    <nav class="navbar">
        <div class="navbar-brand">
            <div class="navbar-logo"></div>
            <div class="navbar-title">USGamNeeds</div>
        </div>
        <div class="navbar-nav">
            <a href="/admin/custom-dashboard" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="/admin/custom-projects" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Projects</span>
            </a>
            <a href="/admin/custom-users" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <span>Users</span>
            </a>
            <a href="/admin/custom-tasks" class="nav-item active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Tasks</span>
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <div class="navbar-logo"></div>
                Edit Task
            </div>
            <div class="page-subtitle">Update task details and settings</div>
        </div>
        
        <!-- Form Container -->
        <div class="form-container">
            <form method="POST" action="/admin/custom-tasks/{{ $task->id }}">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="form-section">
                    <h3 class="section-title">Basic Information</h3>
                    
                    <div class="form-row form-row-full">
                        <div class="form-group">
                            <label class="form-label" for="title">Task Title *</label>
                            <input type="text" id="title" name="title" class="form-input" value="{{ $task->title }}" placeholder="Enter task title" required>
                        </div>
                    </div>
                    
                    <div class="form-row form-row-full">
                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea id="description" name="description" class="form-textarea" placeholder="Describe the task details and requirements">{{ $task->description }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Task Settings -->
                <div class="form-section">
                    <h3 class="section-title">Task Settings</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                                <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="priority">Priority</label>
                            <select id="priority" name="priority" class="form-select">
                                <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $task->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="project_id">Project</label>
                            <select id="project_id" name="project_id" class="form-select">
                                <option value="">Select a project</option>
                                @php
                                    $projects = \App\Models\Project::select(['id', 'name'])->get();
                                @endphp
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ $task->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="created_by">Created By</label>
                            <select id="created_by" name="created_by" class="form-select">
                                @php
                                    $users = \App\Models\User::select(['id', 'name'])->get();
                                @endphp
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->created_by == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Team Assignment -->
                <div class="form-section">
                    <h3 class="section-title">Team Assignment</h3>
                    
                    <div class="form-row form-row-full">
                        <div class="form-group">
                            <label class="form-label" for="assigned_users">Assigned Users</label>
                            <div class="user-select-container">
                                <div class="user-select" id="user-select" onclick="toggleUserDropdown()">
                                    <span id="user-placeholder">Select users to assign to this task</span>
                                </div>
                                <div class="user-dropdown" id="user-dropdown">
                                    @foreach($users as $user)
                                        <div class="user-option" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" onclick="selectUser(this)">
                                            {{ $user->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <input type="hidden" name="assigned_users" id="assigned-users-input">
                        </div>
                    </div>
                </div>
                
                <!-- Timeline & Effort -->
                <div class="form-section">
                    <h3 class="section-title">Timeline & Effort</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="due_date">Due Date</label>
                            <input type="date" id="due_date" name="due_date" class="form-date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="estimated_hours">Estimated Hours</label>
                            <input type="number" id="estimated_hours" name="estimated_hours" class="form-number" value="{{ $task->estimated_hours }}" placeholder="0" min="0" step="0.5">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="actual_hours">Actual Hours</label>
                            <input type="number" id="actual_hours" name="actual_hours" class="form-number" value="{{ $task->actual_hours }}" placeholder="0" min="0" step="0.5">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="assigned_to">Assigned To (Legacy)</label>
                            <select id="assigned_to" name="assigned_to" class="form-select">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="/admin/custom-tasks" class="btn btn-outline">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        let selectedUsers = [];
        
        // Initialize with existing assigned users
        document.addEventListener('DOMContentLoaded', function() {
            @if($task->assignedUsers->count() > 0)
                @foreach($task->assignedUsers as $user)
                    selectedUsers.push({ id: {!! json_encode($user->id) !!}, name: {!! json_encode($user->name) !!} });
                @endforeach
                updateUserDisplay();
            @endif
        });
        
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }
        
        function selectUser(element) {
            const userId = element.dataset.userId;
            const userName = element.dataset.userName;
            
            if (selectedUsers.find(user => user.id === userId)) {
                // Remove user
                selectedUsers = selectedUsers.filter(user => user.id !== userId);
                element.classList.remove('selected');
            } else {
                // Add user
                selectedUsers.push({ id: userId, name: userName });
                element.classList.add('selected');
            }
            
            updateUserDisplay();
        }
        
        function updateUserDisplay() {
            const userSelect = document.getElementById('user-select');
            const placeholder = document.getElementById('user-placeholder');
            const assignedUsersInput = document.getElementById('assigned-users-input');
            
            if (selectedUsers.length === 0) {
                placeholder.style.display = 'block';
                userSelect.innerHTML = '<span id="user-placeholder">Select users to assign to this task</span>';
            } else {
                placeholder.style.display = 'none';
                userSelect.innerHTML = selectedUsers.map(user => 
                    `<div class="user-tag">
                        ${user.name}
                        <span class="user-tag-remove" onclick="removeUser('${user.id}')">×</span>
                    </div>`
                ).join('');
            }
            
            assignedUsersInput.value = selectedUsers.map(user => user.id).join(',');
        }
        
        function removeUser(userId) {
            selectedUsers = selectedUsers.filter(user => user.id !== userId);
            const option = document.querySelector(`[data-user-id="${userId}"]`);
            if (option) {
                option.classList.remove('selected');
            }
            updateUserDisplay();
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const container = document.querySelector('.user-select-container');
            if (!container.contains(event.target)) {
                document.getElementById('user-dropdown').style.display = 'none';
            }
        });
    </script>
</body>
</html>
