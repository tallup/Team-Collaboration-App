<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Board - USGamNeeds Team Collaboration</title>
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
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 32px;
        }
        
        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            font-size: 16px;
            color: #6b7280;
        }
        
        /* Kanban Board */
        .kanban-board {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            height: calc(100vh - 200px);
        }
        
        .kanban-column {
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .column-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background: white;
        }
        
        .column-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .column-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        
        .column-dot.todo { background: #6b7280; }
        .column-dot.in-progress { background: #3b82f6; }
        .column-dot.review { background: #8b5cf6; }
        .column-dot.completed { background: #f59e0b; }
        
        .column-count {
            background: #e5e7eb;
            color: #6b7280;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .column-content {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .task-card {
            background: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            cursor: grab;
            transition: all 0.2s;
        }
        
        .task-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .task-card.dragging {
            opacity: 0.5;
            transform: rotate(5deg);
        }
        
        .task-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .task-description {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .task-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .task-priority {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .task-priority.low { background: #d1fae5; color: #065f46; }
        .task-priority.medium { background: #fef3c7; color: #92400e; }
        .task-priority.high { background: #fed7d7; color: #991b1b; }
        .task-priority.urgent { background: #fecaca; color: #7f1d1d; }
        
        .task-due-date {
            font-size: 11px;
            color: #6b7280;
        }
        
        .task-assignees {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .assignee-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: 600;
            border: 2px solid white;
        }
        
        .empty-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #9ca3af;
            text-align: center;
        }
        
        .empty-column svg {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }
        
        .empty-column-text {
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Drag and Drop */
        .column-content.drag-over {
            background: rgba(102, 126, 234, 0.05);
            border: 2px dashed #667eea;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .kanban-board {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .kanban-board {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
            <a href="/admin/custom-tasks" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>Tasks</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Task Board</h1>
            <p class="page-subtitle">Organize and track your tasks by status</p>
        </div>

        <!-- Kanban Board -->
        <div class="kanban-board">
            <!-- Todo Column -->
            <div class="kanban-column">
                <div class="column-header">
                    <div class="column-title">
                        <div class="column-dot todo"></div>
                        <span>Todo</span>
                    </div>
                    <div class="column-count" id="todo-count">{{ $tasks->where('status', 'todo')->count() }}</div>
                </div>
                <div class="column-content" data-status="todo" ondrop="drop(event)" ondragover="allowDrop(event)">
                    @forelse($tasks->where('status', 'todo') as $task)
                        <div class="task-card" draggable="true" ondragstart="drag(event)" data-task-id="{{ $task->id }}">
                            <div class="task-title">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="task-description">{{ $task->description }}</div>
                            @endif
                            <div class="task-meta">
                                <span class="task-priority {{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                                @if($task->due_date)
                                    <span class="task-due-date">{{ $task->due_date->format('M j') }}</span>
                                @endif
                            </div>
                            @if($task->assignedUsers->count() > 0)
                                <div class="task-assignees">
                                    @foreach($task->assignedUsers->take(3) as $user)
                                        <div class="assignee-avatar" title="{{ $user->name }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($task->assignedUsers->count() > 3)
                                        <div class="assignee-avatar">+{{ $task->assignedUsers->count() - 3 }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-column">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <div class="empty-column-text">No tasks</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="kanban-column">
                <div class="column-header">
                    <div class="column-title">
                        <div class="column-dot in-progress"></div>
                        <span>In Progress</span>
                    </div>
                    <div class="column-count" id="in-progress-count">{{ $tasks->where('status', 'in_progress')->count() }}</div>
                </div>
                <div class="column-content" data-status="in_progress" ondrop="drop(event)" ondragover="allowDrop(event)">
                    @forelse($tasks->where('status', 'in_progress') as $task)
                        <div class="task-card" draggable="true" ondragstart="drag(event)" data-task-id="{{ $task->id }}">
                            <div class="task-title">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="task-description">{{ $task->description }}</div>
                            @endif
                            <div class="task-meta">
                                <span class="task-priority {{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                                @if($task->due_date)
                                    <span class="task-due-date">{{ $task->due_date->format('M j') }}</span>
                                @endif
                            </div>
                            @if($task->assignedUsers->count() > 0)
                                <div class="task-assignees">
                                    @foreach($task->assignedUsers->take(3) as $user)
                                        <div class="assignee-avatar" title="{{ $user->name }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($task->assignedUsers->count() > 3)
                                        <div class="assignee-avatar">+{{ $task->assignedUsers->count() - 3 }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-column">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <div class="empty-column-text">No tasks</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Review Column -->
            <div class="kanban-column">
                <div class="column-header">
                    <div class="column-title">
                        <div class="column-dot review"></div>
                        <span>Review</span>
                    </div>
                    <div class="column-count" id="review-count">{{ $tasks->where('status', 'review')->count() }}</div>
                </div>
                <div class="column-content" data-status="review" ondrop="drop(event)" ondragover="allowDrop(event)">
                    @forelse($tasks->where('status', 'review') as $task)
                        <div class="task-card" draggable="true" ondragstart="drag(event)" data-task-id="{{ $task->id }}">
                            <div class="task-title">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="task-description">{{ $task->description }}</div>
                            @endif
                            <div class="task-meta">
                                <span class="task-priority {{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                                @if($task->due_date)
                                    <span class="task-due-date">{{ $task->due_date->format('M j') }}</span>
                                @endif
                            </div>
                            @if($task->assignedUsers->count() > 0)
                                <div class="task-assignees">
                                    @foreach($task->assignedUsers->take(3) as $user)
                                        <div class="assignee-avatar" title="{{ $user->name }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($task->assignedUsers->count() > 3)
                                        <div class="assignee-avatar">+{{ $task->assignedUsers->count() - 3 }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-column">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <div class="empty-column-text">No tasks</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Completed Column -->
            <div class="kanban-column">
                <div class="column-header">
                    <div class="column-title">
                        <div class="column-dot completed"></div>
                        <span>Completed</span>
                    </div>
                    <div class="column-count" id="completed-count">{{ $tasks->where('status', 'completed')->count() }}</div>
                </div>
                <div class="column-content" data-status="completed" ondrop="drop(event)" ondragover="allowDrop(event)">
                    @forelse($tasks->where('status', 'completed') as $task)
                        <div class="task-card" draggable="true" ondragstart="drag(event)" data-task-id="{{ $task->id }}">
                            <div class="task-title">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="task-description">{{ $task->description }}</div>
                            @endif
                            <div class="task-meta">
                                <span class="task-priority {{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                                @if($task->due_date)
                                    <span class="task-due-date">{{ $task->due_date->format('M j') }}</span>
                                @endif
                            </div>
                            @if($task->assignedUsers->count() > 0)
                                <div class="task-assignees">
                                    @foreach($task->assignedUsers->take(3) as $user)
                                        <div class="assignee-avatar" title="{{ $user->name }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($task->assignedUsers->count() > 3)
                                        <div class="assignee-avatar">+{{ $task->assignedUsers->count() - 3 }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-column">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <div class="empty-column-text">No tasks</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <script>
        let draggedElement = null;

        function allowDrop(ev) {
            ev.preventDefault();
            ev.currentTarget.classList.add('drag-over');
        }

        function drag(ev) {
            draggedElement = ev.target;
            ev.target.classList.add('dragging');
        }

        function drop(ev) {
            ev.preventDefault();
            ev.currentTarget.classList.remove('drag-over');
            
            if (draggedElement && ev.currentTarget.classList.contains('column-content')) {
                const newStatus = ev.currentTarget.getAttribute('data-status');
                const taskId = draggedElement.getAttribute('data-task-id');
                
                // Update task status via AJAX
                updateTaskStatus(taskId, newStatus);
                
                // Move the task card to the new column
                ev.currentTarget.appendChild(draggedElement);
                
                // Update column counts
                updateColumnCounts();
            }
            
            draggedElement.classList.remove('dragging');
            draggedElement = null;
        }

        function updateTaskStatus(taskId, newStatus) {
            fetch(`/admin/custom-tasks/${taskId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Task status updated:', data);
            })
            .catch(error => {
                console.error('Error updating task status:', error);
            });
        }

        function updateColumnCounts() {
            const columns = ['todo', 'in_progress', 'review', 'completed'];
            columns.forEach(status => {
                const column = document.querySelector(`[data-status="${status}"]`);
                const count = column.querySelectorAll('.task-card').length;
                document.getElementById(`${status}-count`).textContent = count;
            });
        }

        // Remove drag-over class when leaving drop zone
        document.querySelectorAll('.column-content').forEach(column => {
            column.addEventListener('dragleave', function(e) {
                if (!this.contains(e.relatedTarget)) {
                    this.classList.remove('drag-over');
                }
            });
        });
    </script>
</body>
</html>
