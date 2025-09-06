<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - USGamNeeds Team Collaboration</title>
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
            justify-content: center;
            flex: 1;
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
        
        /* Profile Dropdown */
        .profile-section {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .profile-picture {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        
        .profile-picture:hover {
            border-color: #667eea;
            transform: scale(1.05);
        }
        
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        
        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .profile-dropdown-header {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .profile-dropdown-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }
        
        .profile-dropdown-email {
            font-size: 14px;
            color: #6b7280;
        }
        
        .profile-dropdown-menu {
            padding: 8px 0;
        }
        
        .profile-dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .profile-dropdown-item:hover {
            background-color: #f9fafb;
            color: #667eea;
        }
        
        .profile-dropdown-item svg {
            width: 16px;
            height: 16px;
            margin-right: 12px;
        }
        
        .profile-dropdown-item.logout {
            color: #dc2626;
            border-top: 1px solid #f3f4f6;
        }
        
        .profile-dropdown-item.logout:hover {
            background-color: #fef2f2;
            color: #dc2626;
        }
        
        /* Main Content */
        .main-content {
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
        }
        
        /* Dashboard Header */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 32px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .dashboard-header::before {
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
        
        .dashboard-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .dashboard-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 24px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.2);
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 4px;
            color: white;
        }
        
        .stat-label {
            font-size: 14px;
            color: white;
            opacity: 1;
        }
        
        .dashboard-time {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: right;
        }
        
        .time-date {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .time-time {
            font-size: 12px;
            opacity: 0.6;
        }
        
        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }
        
        .content-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #1f2937;
        }
        
        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .task-item:last-child {
            border-bottom: none;
        }
        
        .task-info {
            flex: 1;
        }
        
        .task-title {
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 4px;
        }
        
        .task-project {
            font-size: 12px;
            color: #6b7280;
        }
        
        .task-status {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-in-progress {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }
        
        .action-btn svg {
            width: 16px;
            height: 16px;
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
            
            .dashboard-header {
                padding: 24px;
            }
            
            .dashboard-title {
                font-size: 24px;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
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
            <a href="/admin/custom-dashboard" class="nav-item active">
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
            <a href="/admin/custom-tasks" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Tasks</span>
            </a>
            <a href="/admin/chat" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <span>Chat</span>
            </a>
            <a href="/admin/custom-users" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <span>Users</span>
            </a>
        </div>
        
        <!-- Profile Section -->
        <div class="profile-section">
            <div class="profile-picture" onclick="toggleProfileDropdown()">
                @if(auth()->user() && auth()->user()->profile_picture)
                    <img src="{{ asset(auth()->user()->profile_picture) }}" alt="Profile Picture" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-dropdown-header">
                    <div class="profile-dropdown-name">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="profile-dropdown-email">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                </div>
                <div class="profile-dropdown-menu">
                    <a href="/admin/profile" class="profile-dropdown-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        View Profile
                    </a>
                    <a href="/admin/profile/edit" class="profile-dropdown-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        Change Password
                    </a>
                    <a href="#" class="profile-dropdown-item logout" onclick="logout()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-title">
                <div class="navbar-logo"></div>
                USGamNeeds Team Collaboration
            </div>
            <div class="dashboard-subtitle">Welcome back! Here is your project overview</div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Task::count() }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Project::where('status', 'active')->count() }}</div>
                    <div class="stat-label">Active Projects</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\User::count() }}</div>
                    <div class="stat-label">Team Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Task::count() > 0 ? round((\App\Models\Task::where('status', 'completed')->count() / \App\Models\Task::count()) * 100) : 0 }}%</div>
                    <div class="stat-label">Completion Rate</div>
                </div>
            </div>
            
            <div class="dashboard-time">
                <div class="time-date">{{ date('l, F j, Y') }}</div>
                <div class="time-time">{{ date('g:i A') }}</div>
            </div>
        </div>
        
        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Recent Tasks -->
            <div class="content-card">
                <h2 class="card-title">Recent Tasks</h2>
                @php
                    $recentTasks = \App\Models\Task::with(['project:id,name'])
                        ->select(['id', 'title', 'status', 'project_id', 'created_at'])
                        ->latest()
                        ->limit(5)
                        ->get();
                @endphp
                @forelse($recentTasks as $task)
                    <div class="task-item" onclick="openTaskModal({{ $task->id }})" style="cursor: pointer;">
                        <div class="task-info">
                            <div class="task-title">{{ $task->title }}</div>
                            <div class="task-project">{{ $task->project->name ?? 'No Project' }}</div>
                        </div>
                        <div class="task-status status-{{ str_replace(' ', '-', strtolower($task->status)) }}">
                            {{ ucfirst($task->status) }}
                        </div>
                    </div>
                @empty
                    <p style="color: #6b7280; text-align: center; padding: 20px;">No tasks found</p>
                @endforelse
            </div>
            
            <!-- Quick Actions -->
            <div class="content-card">
                <h2 class="card-title">Quick Actions</h2>
                <div class="quick-actions">
                    <a href="/admin/custom-tasks/create" class="action-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        New Task
                    </a>
                    <a href="/admin/custom-projects/create" class="action-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        New Project
                    </a>
                    <a href="/admin/custom-users" class="action-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Add User
                    </a>
                    <a href="/admin/custom-tasks" class="action-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        View All Tasks
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Task Modal -->
    <div id="taskModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTaskTitle">Task Details</h2>
                <button class="modal-close" onclick="closeTaskModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalTaskBody">
                <!-- Task details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Task data for modal - will be populated by PHP
        const taskData = {};
    </script>

    <script>
        // Populate task data
        // @ts-nocheck
        @php
            $allTasks = \App\Models\Task::with(['project:id,name', 'assignedUsers:id,name', 'createdBy:id,name'])
                ->select(['id', 'title', 'description', 'status', 'priority', 'project_id', 'created_by', 'due_date', 'created_at', 'updated_at'])
                ->get();
        @endphp
        
        @foreach($allTasks as $task)
        taskData[{{ $task->id }}] = {
            id: {{ $task->id }},
            title: {!! json_encode($task->title) !!},
            description: {!! json_encode($task->description ?? '') !!},
            status: {!! json_encode($task->status) !!},
            priority: {!! json_encode($task->priority) !!},
            project: {!! json_encode($task->project->name ?? 'No Project') !!},
            createdBy: {!! json_encode($task->createdBy->name ?? 'Unknown') !!},
            dueDate: {!! json_encode($task->due_date ? $task->due_date->format('M j, Y') : 'No due date') !!},
            createdAt: {!! json_encode($task->created_at->format('M j, Y')) !!},
            updatedAt: {!! json_encode($task->updated_at->format('M j, Y')) !!},
            assignedUsers: [
                @foreach($task->assignedUsers as $user)
                {!! json_encode($user->name) !!}{{ $loop->last ? '' : ',' }}
                @endforeach
            ]
        };
        @endforeach
    </script>

    <script>

        function openTaskModal(taskId) {
            const task = taskData[taskId];
            if (!task) return;

            document.getElementById('modalTaskTitle').textContent = task.title;
            
            const modalBody = document.getElementById('modalTaskBody');
            modalBody.innerHTML = `
                <div class="modal-content-left">
                    <div class="task-detail-grid">
                        <div class="detail-item">
                            <label>Description</label>
                            <p>${task.description || 'No description available'}</p>
                        </div>
                        <div class="detail-item">
                            <label>Project</label>
                            <p>${task.project}</p>
                        </div>
                        <div class="detail-item">
                            <label>Status</label>
                            <select onchange="changeTaskStatus(${task.id}, this.value)" class="status-select">
                                <option value="todo" ${task.status === 'todo' ? 'selected' : ''}>Todo</option>
                                <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                <option value="review" ${task.status === 'review' ? 'selected' : ''}>Review</option>
                                <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                            </select>
                        </div>
                        <div class="detail-item">
                            <label>Priority</label>
                            <span class="priority-badge priority-${task.priority}">${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}</span>
                        </div>
                        <div class="detail-item">
                            <label>Created By</label>
                            <p>${task.createdBy}</p>
                        </div>
                        <div class="detail-item">
                            <label>Due Date</label>
                            <p>${task.dueDate}</p>
                        </div>
                        <div class="detail-item">
                            <label>Created</label>
                            <p>${task.createdAt}</p>
                        </div>
                        <div class="detail-item">
                            <label>Last Updated</label>
                            <p>${task.updatedAt}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-content-right">
                    <div class="comments-section">
                        <h3>Activity</h3>
                        <div class="comments-list" id="commentsList">
                            <p style="color: #6b7280; text-align: center; padding: 20px;">No comments yet</p>
                        </div>
                        <div class="comment-form">
                            <div class="comment-form-wrapper">
                                <textarea placeholder="Write a comment..."></textarea>
                                <button class="comment-send-btn" onclick="addComment(${task.id})">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('taskModal').style.display = 'flex';
            
            // Load existing comments
            loadComments(task.id);
        }

        function closeTaskModal() {
            document.getElementById('taskModal').style.display = 'none';
        }

        function changeTaskStatus(taskId, newStatus) {
            fetch(`/admin/custom-tasks/${taskId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatusUpdateMessage('Task status updated successfully!');
                    // Update the task data
                    taskData[taskId].status = newStatus;
                } else {
                    showStatusUpdateMessage('Failed to update task status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusUpdateMessage('Failed to update task status', 'error');
            });
        }

        function showStatusUpdateMessage(message, type = 'success') {
            const messageDiv = document.createElement('div');
            messageDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
            `;
            messageDiv.textContent = message;
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        function loadComments(taskId) {
            const commentsList = document.getElementById('commentsList');
            if (!commentsList) return;
            
            fetch(`/admin/custom-tasks/${taskId}/comments`)
                .then(response => response.json())
                .then(comments => {
                    if (comments.length === 0) {
                        commentsList.innerHTML = '<p style="color: #6b7280; text-align: center; padding: 20px;">No comments yet</p>';
                        return;
                    }
                    
                    const commentsHtml = comments.map(comment => `
                        <div class="comment-item">
                            <div class="comment-bullet"></div>
                            <div class="comment-content-wrapper">
                                <div class="comment-text">${comment.content}</div>
                                <div class="comment-meta">
                                    <span class="comment-author">${comment.user_name}</span>
                                    <span class="comment-date">${comment.created_at}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    
                    commentsList.innerHTML = commentsHtml;
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    commentsList.innerHTML = '<p style="color: #6b7280; text-align: center; padding: 20px;">Error loading comments.</p>';
                });
        }
        
        function addComment(taskId) {
            const textarea = document.querySelector('.comment-form textarea');
            const comment = textarea.value.trim();
            if (!comment) return;

            // Save comment to database
            fetch(`/admin/custom-tasks/${taskId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    content: comment
                })
            })
            .then(response => response.json())
            .then(data => {
                const commentsList = document.getElementById('commentsList');
                if (commentsList.querySelector('p')) {
                    commentsList.innerHTML = '';
                }

                const commentDiv = document.createElement('div');
                commentDiv.className = 'comment-item';
                commentDiv.innerHTML = `
                    <div class="comment-bullet"></div>
                    <div class="comment-content-wrapper">
                        <div class="comment-text">${data.comment.content}</div>
                        <div class="comment-meta">
                            <span class="comment-author">${data.comment.user_name}</span>
                            <span class="comment-date">${data.comment.created_at}</span>
                        </div>
                    </div>
                `;
                commentsList.appendChild(commentDiv);
                textarea.value = '';
            })
            .catch(error => {
                console.error('Error adding comment:', error);
            });
        }

        // Close modal when clicking outside
        document.getElementById('taskModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTaskModal();
            }
        });

        // Profile Dropdown Functions
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Create a form to submit logout request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken.getAttribute('content');
                    form.appendChild(csrfInput);
                }
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const profileSection = document.querySelector('.profile-section');
            const dropdown = document.getElementById('profileDropdown');
            
            if (profileSection && !profileSection.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>

    <style>
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 1000px;
            width: 90%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            background: #f8fafc;
            color: #1f2937;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
            min-height: 0;
            display: flex;
            gap: 24px;
        }

        .modal-content-left {
            flex: 2;
            min-width: 0;
        }

        .modal-content-right {
            flex: 1;
            min-width: 300px;
        }

        .task-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-item label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .detail-item p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .status-select {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background: white;
        }

        .priority-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .priority-low { background: #d1fae5; color: #065f46; }
        .priority-medium { background: #fef3c7; color: #92400e; }
        .priority-high { background: #fed7d7; color: #991b1b; }
        .priority-urgent { background: #fecaca; color: #7f1d1d; }

        /* Activity Feed Style Comments Section */
        .comments-section {
            background: #ffffff;
            border-radius: 12px;
            height: 500px;
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .comments-section h3 {
            background: #ffffff;
            color: #1f2937;
            margin: 0;
            padding: 20px 24px 16px 24px;
            font-size: 18px;
            font-weight: 700;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .comments-list {
            flex: 1;
            overflow-y: auto;
            padding: 0;
            background: #ffffff;
            display: flex;
            flex-direction: column;
        }
        
        .comment-item {
            background: transparent;
            border: none;
            padding: 16px 24px;
            margin: 0;
            border-bottom: 1px solid #f3f4f6;
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .comment-item:last-child {
            border-bottom: none;
        }
        
        .comment-bullet {
            width: 6px;
            height: 6px;
            background: #9ca3af;
            border-radius: 50%;
            margin-top: 8px;
            flex-shrink: 0;
        }
        
        .comment-content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .comment-text {
            color: #374151;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }
        
        .comment-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
        }
        
        .comment-author {
            font-weight: 600;
            color: #1f2937;
            font-size: 13px;
        }
        
        .comment-date {
            color: #6b7280;
            font-size: 12px;
        }
        
        .comment-form {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 20px 24px;
        }
        
        .comment-form-wrapper {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }
        
        .comment-form textarea {
            flex: 1;
            min-height: 40px;
            max-height: 120px;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: none;
            background: #ffffff;
            outline: none;
            transition: all 0.2s;
        }
        
        .comment-form textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .comment-send-btn {
            background: #6b7280;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            height: 40px;
        }
        
        .comment-send-btn:hover {
            background: #4b5563;
        }


        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .comment-header strong {
            font-size: 14px;
            color: #1f2937;
        }

        .comment-header span {
            font-size: 12px;
            color: #6b7280;
        }

        .comment-content {
            font-size: 14px;
            color: #374151;
            line-height: 1.5;
        }

        .comment-form {
            margin-top: 16px;
        }

        .comment-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            resize: vertical;
            margin-bottom: 8px;
        }

        .comment-form button {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .comment-form button:hover {
            background: #5a67d8;
        }

        @media (max-width: 768px) {
            .modal-body {
                flex-direction: column;
            }
            
            .modal-content-right {
                min-width: auto;
            }
            
            .task-detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
