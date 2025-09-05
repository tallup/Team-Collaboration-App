<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tasks - USGamNeeds Team Collaboration</title>
    <!-- Updated: {{ now() }} - Profile Pictures Fix v2 -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
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
            margin-bottom: 24px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 16px;
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
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }
        
        /* Filters */
        .filters {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
        }
        
        .filters-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            align-items: end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .filter-select {
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }
        
        .filter-select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .search-input {
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        /* Kanban Board */
        .kanban-board {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 20px 0;
            margin-bottom: 32px;
            background: #f8fafc;
            border-radius: 12px;
            min-height: 600px;
        }
        
        .kanban-column {
            min-width: 300px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            max-height: 100%;
        }
        
        .column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 8px 8px 0 0;
        }
        
        .column-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .task-count {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }
        
        .column-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 16px;
            flex: 1;
            overflow-y: auto;
        }
        
        .kanban-column.todo .column-header {
            background: #f3f4f6;
        }
        
        .kanban-column.in-progress .column-header {
            background: #dbeafe;
        }
        
        .kanban-column.review .column-header {
            background: #fef3c7;
        }
        
        .kanban-column.completed .column-header {
            background: #d1fae5;
        }
        
        /* Tasks Grid (fallback) */
        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .task-card {
            background: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 12px;
        }
        
        .task-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .clickable-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .clickable-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }
        
        .clickable-card:active {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .task-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        
        .task-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 6px;
            line-height: 1.3;
        }
        
        .task-description {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 12px;
        }
        
        .task-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-todo {
            background: #f3f4f6;
            color: #374151;
        }
        
        .status-in-progress {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-review {
            background: #e9d5ff;
            color: #7c3aed;
        }
        
        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .task-priority {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: inline-block;
        }
        
        .priority-low {
            background: #d1fae5;
            color: #065f46;
        }
        
        .priority-medium {
            background: #fef3c7;
            color: #92400e;
        }
        
        .priority-high {
            background: #fed7aa;
            color: #c2410c;
        }
        
        .priority-urgent {
            background: #fecaca;
            color: #dc2626;
        }
        
        .task-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-top: 12px;
            border-top: 1px solid #f3f4f6;
            font-size: 11px;
        }
        
        .task-project {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-size: 14px;
        }
        
        .task-dates {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 12px;
            color: #6b7280;
        }
        
        .task-assignees {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            min-height: 32px;
        }
        
        .assignee-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }
        
        .assignee-avatars {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            max-width: 100%;
        }
        
        .assignee-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .avatar-more {
            background: #6b7280 !important;
            font-size: 10px;
        }
        
        .avatar-1 { background: #8b5cf6; }
        .avatar-2 { background: #f59e0b; }
        .avatar-3 { background: #10b981; }
        .avatar-4 { background: #ef4444; }
        
        .task-actions {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 12px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
            color: #374151;
        }
        
        /* Create Task Button */
        .create-task-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            z-index: 100;
        }
        
        .create-task-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .create-task-btn svg {
            width: 24px;
            height: 24px;
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
            
            .filters-row {
                grid-template-columns: 1fr;
            }
            
            .tasks-grid {
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
            <a href="/admin/custom-tasks" class="nav-item active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Tasks</span>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 01 7 9z"></path>
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
        <!-- Success Message -->
        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <div class="navbar-logo"></div>
                Tasks Overview
            </div>
            <div class="page-subtitle">Manage and track all your team tasks</div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Task::count() }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Task::where('status', 'in_progress')->count() }}</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Task::where('status', 'completed')->count() }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Task::where('status', 'todo')->count() }}</div>
                    <div class="stat-label">To Do</div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters">
            <div class="filters-row">
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-select" id="status-filter">
                        <option value="">All Statuses</option>
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Priority</label>
                    <select class="filter-select" id="priority-filter">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Project</label>
                    <select class="filter-select" id="project-filter">
                        <option value="">All Projects</option>
                        @php
                            $projects = \App\Models\Project::select(['id', 'name'])->get();
                        @endphp
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input type="text" class="search-input" id="search-input" placeholder="Search tasks...">
                </div>
            </div>
        </div>
        
        <!-- Kanban Board -->
        <div class="kanban-board" id="kanban-board">
            <!-- TODO Column -->
            <div class="kanban-column todo">
                <div class="column-header">
                    <h3 class="column-title">To Do</h3>
                    <span class="task-count">{{ $tasks->whereIn('status', ['TODO', 'todo', 'To Do', 'TO_DO', 'to_do'])->count() }}</span>
                </div>
                <div class="column-content">
                    @foreach($tasks->whereIn('status', ['TODO', 'todo', 'To Do', 'TO_DO', 'to_do']) as $task)
                        <div class="task-card clickable-card" data-task-id="{{ $task->id }}" data-status="{{ $task->status }}" data-priority="{{ $task->priority }}" data-project="{{ $task->project_id }}" data-title="{{ strtolower($task->title) }}" onclick="openTaskModal({{ $task->id }})">
                            <div class="task-header">
                                <div>
                                    <h3 class="task-title">{{ $task->title }}</h3>
                                    <p class="task-description">{{ $task->description ?? 'No description available' }}</p>
                                </div>
                                <div class="task-status status-{{ str_replace(' ', '-', $task->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </div>
                            </div>
                            
                            @if($task->priority)
                                <div class="task-priority priority-{{ $task->priority }}">
                                    {{ ucfirst($task->priority) }} Priority
                                </div>
                            @endif
                            
                            @if($task->assignedUsers->count() > 0)
                                <div class="task-assignees">
                                    <span class="assignee-label">Assigned to ({{ $task->assignedUsers->count() }}):</span>
                                    <div class="assignee-avatars">
                                        @foreach($task->assignedUsers->take(4) as $index => $user)
                                            <div class="assignee-avatar avatar-{{ ($index % 4) + 1 }}" title="{{ $user->name }}">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($task->assignedUsers->count() > 4)
                                            <div class="assignee-avatar avatar-more" title="+{{ $task->assignedUsers->count() - 4 }} more">
                                                +{{ $task->assignedUsers->count() - 4 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="task-meta">
                                <div class="task-project">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    {{ $task->project->name ?? 'No Project' }}
                                </div>
                                <div class="task-dates">
                                    @if($task->due_date)
                                        <div>Due: {{ $task->due_date?->format('M j, Y') ?? 'Not set' }}</div>
                                    @endif
                                    <div>Created: {{ $task->created_at->format('M j, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- IN PROGRESS Column -->
            <div class="kanban-column in-progress">
                <div class="column-header">
                    <h3 class="column-title">In Progress</h3>
                    <span class="task-count">{{ $tasks->whereIn('status', ['IN PROGRESS', 'in progress', 'In Progress', 'IN_PROGRESS', 'INPROGRESS', 'in_progress'])->count() }}</span>
                </div>
                <div class="column-content">
                    @foreach($tasks->whereIn('status', ['IN PROGRESS', 'in progress', 'In Progress', 'IN_PROGRESS', 'INPROGRESS', 'in_progress']) as $task)
                        <div class="task-card clickable-card" data-task-id="{{ $task->id }}" data-status="{{ $task->status }}" data-priority="{{ $task->priority }}" data-project="{{ $task->project_id }}" data-title="{{ strtolower($task->title) }}" onclick="openTaskModal({{ $task->id }})">
                            <div class="task-header">
                                <div>
                                    <h3 class="task-title">{{ $task->title }}</h3>
                                    <p class="task-description">{{ $task->description ?? 'No description available' }}</p>
                                </div>
                                <div class="task-status status-{{ str_replace(' ', '-', $task->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </div>
                            </div>
                            
                            @if($task->priority)
                                <div class="task-priority priority-{{ $task->priority }}">
                                    {{ ucfirst($task->priority) }} Priority
                                </div>
                            @endif
                            
                            @if($task->assignedUsers->count() > 0)
                                <div class="task-assignees">
                                    <span class="assignee-label">Assigned to ({{ $task->assignedUsers->count() }}):</span>
                                    <div class="assignee-avatars">
                                        @foreach($task->assignedUsers->take(4) as $index => $user)
                                            <div class="assignee-avatar avatar-{{ ($index % 4) + 1 }}" title="{{ $user->name }}">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($task->assignedUsers->count() > 4)
                                            <div class="assignee-avatar avatar-more" title="+{{ $task->assignedUsers->count() - 4 }} more">
                                                +{{ $task->assignedUsers->count() - 4 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="task-meta">
                                <div class="task-project">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    {{ $task->project->name ?? 'No Project' }}
                                </div>
                                <div class="task-dates">
                                    @if($task->due_date)
                                        <div>Due: {{ $task->due_date?->format('M j, Y') ?? 'Not set' }}</div>
                                    @endif
                                    <div>Created: {{ $task->created_at->format('M j, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- REVIEW Column -->
            <div class="kanban-column review">
                <div class="column-header">
                    <h3 class="column-title">Review</h3>
                    <span class="task-count">{{ $tasks->whereIn('status', ['REVIEW', 'review', 'Review', 'in_review'])->count() }}</span>
                </div>
                <div class="column-content">
                    @foreach($tasks->whereIn('status', ['REVIEW', 'review', 'Review', 'in_review']) as $task)
                        <div class="task-card clickable-card" data-task-id="{{ $task->id }}" data-status="{{ $task->status }}" data-priority="{{ $task->priority }}" data-project="{{ $task->project_id }}" data-title="{{ strtolower($task->title) }}" onclick="openTaskModal({{ $task->id }})">
                            <div class="task-header">
                                <div>
                                    <h3 class="task-title">{{ $task->title }}</h3>
                                    <p class="task-description">{{ $task->description ?? 'No description available' }}</p>
                                </div>
                                <div class="task-status status-{{ str_replace(' ', '-', $task->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </div>
                            </div>
                            
                            @if($task->priority)
                                <div class="task-priority priority-{{ $task->priority }}">
                                    {{ ucfirst($task->priority) }} Priority
                                </div>
                            @endif
                            
                            @if($task->assignedUsers->count() > 0)
                                <div class="task-assignees">
                                    <span class="assignee-label">Assigned to ({{ $task->assignedUsers->count() }}):</span>
                                    <div class="assignee-avatars">
                                        @foreach($task->assignedUsers->take(4) as $index => $user)
                                            <div class="assignee-avatar avatar-{{ ($index % 4) + 1 }}" title="{{ $user->name }}">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($task->assignedUsers->count() > 4)
                                            <div class="assignee-avatar avatar-more" title="+{{ $task->assignedUsers->count() - 4 }} more">
                                                +{{ $task->assignedUsers->count() - 4 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="task-meta">
                                <div class="task-project">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    {{ $task->project->name ?? 'No Project' }}
                                </div>
                                <div class="task-dates">
                                    @if($task->due_date)
                                        <div>Due: {{ $task->due_date?->format('M j, Y') ?? 'Not set' }}</div>
                                    @endif
                                    <div>Created: {{ $task->created_at->format('M j, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- COMPLETED Column -->
            <div class="kanban-column completed">
                <div class="column-header">
                    <h3 class="column-title">Completed</h3>
                    <span class="task-count">{{ $tasks->whereIn('status', ['COMPLETED', 'completed', 'Completed', 'DONE', 'done', 'Done'])->count() }}</span>
                </div>
                <div class="column-content">
                    @foreach($tasks->whereIn('status', ['COMPLETED', 'completed', 'Completed', 'DONE', 'done', 'Done']) as $task)
                        <div class="task-card clickable-card" data-task-id="{{ $task->id }}" data-status="{{ $task->status }}" data-priority="{{ $task->priority }}" data-project="{{ $task->project_id }}" data-title="{{ strtolower($task->title) }}" onclick="openTaskModal({{ $task->id }})">
                            <div class="task-header">
                                <div>
                                    <h3 class="task-title">{{ $task->title }}</h3>
                                    <p class="task-description">{{ $task->description ?? 'No description available' }}</p>
                                </div>
                                <div class="task-status status-{{ str_replace(' ', '-', $task->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </div>
                            </div>
                            
                            @if($task->priority)
                                <div class="task-priority priority-{{ $task->priority }}">
                                    {{ ucfirst($task->priority) }} Priority
                                </div>
                            @endif
                            
                            @if($task->assignedUsers->count() > 0)
                                <div class="task-assignees">
                                    <span class="assignee-label">Assigned to ({{ $task->assignedUsers->count() }}):</span>
                                    <div class="assignee-avatars">
                                        @foreach($task->assignedUsers->take(4) as $index => $user)
                                            <div class="assignee-avatar avatar-{{ ($index % 4) + 1 }}" title="{{ $user->name }}">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($task->assignedUsers->count() > 4)
                                            <div class="assignee-avatar avatar-more" title="+{{ $task->assignedUsers->count() - 4 }} more">
                                                +{{ $task->assignedUsers->count() - 4 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="task-meta">
                                <div class="task-project">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    {{ $task->project->name ?? 'No Project' }}
                                </div>
                                <div class="task-dates">
                                    @if($task->due_date)
                                        <div>Due: {{ $task->due_date?->format('M j, Y') ?? 'Not set' }}</div>
                                    @endif
                                    <div>Created: {{ $task->created_at->format('M j, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        @if($tasks->count() === 0)
            <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; opacity: 0.5;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 style="font-size: 18px; margin-bottom: 8px;">No tasks found</h3>
                <p style="font-size: 14px; margin-bottom: 24px;">Get started by creating your first task</p>
                <a href="/admin/custom-tasks/create" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Task
                </a>
            </div>
        @endif
    </main>
    
    <!-- Floating Create Button -->
    <a href="/admin/custom-tasks/create" class="create-task-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
    
    <script>
        // Organize tasks into correct Kanban columns based on status
        function organizeKanbanBoard() {
            const taskCards = document.querySelectorAll('.task-card');
            const kanbanColumns = {
                'todo': document.querySelector('.kanban-column.todo .column-content'),
                'in_progress': document.querySelector('.kanban-column.in-progress .column-content'),
                'review': document.querySelector('.kanban-column.review .column-content'),
                'completed': document.querySelector('.kanban-column.completed .column-content')
            };
            
            // Clear all columns first
            Object.values(kanbanColumns).forEach(column => {
                if (column) {
                    column.innerHTML = '';
                }
            });
            
            // Move each task to the correct column based on its status
            taskCards.forEach(card => {
                const status = card.dataset.status.toLowerCase().replace(/\s+/g, '_');
                let targetColumn = null;
                
                // Map status to column
                if (status === 'todo' || status === 'to_do') {
                    targetColumn = kanbanColumns.todo;
                } else if (status === 'in_progress' || status === 'inprogress') {
                    targetColumn = kanbanColumns.in_progress;
                } else if (status === 'review' || status === 'in_review') {
                    targetColumn = kanbanColumns.review;
                } else if (status === 'completed' || status === 'done') {
                    targetColumn = kanbanColumns.completed;
                }
                
                if (targetColumn) {
                    targetColumn.appendChild(card);
                    card.style.display = 'block';
                }
            });
            
            // Update task counts for each column
            Object.keys(kanbanColumns).forEach(status => {
                const column = kanbanColumns[status];
                const countElement = column.parentElement.querySelector('.task-count');
                if (countElement && column) {
                    const visibleTasks = column.querySelectorAll('.task-card');
                    countElement.textContent = visibleTasks.length;
                }
            });
        }
        
        // Filter functionality for Kanban board
        function filterTasks() {
            const statusFilter = document.getElementById('status-filter').value;
            const priorityFilter = document.getElementById('priority-filter').value;
            const projectFilter = document.getElementById('project-filter').value;
            const searchInput = document.getElementById('search-input').value.toLowerCase();
            
            const taskCards = document.querySelectorAll('.task-card');
            
            // First organize tasks into correct columns
            organizeKanbanBoard();
            
            // Then apply filters
            taskCards.forEach(card => {
                const status = card.dataset.status;
                const priority = card.dataset.priority;
                const project = card.dataset.project;
                const title = card.dataset.title;
                
                let show = true;
                
                if (statusFilter && status !== statusFilter) show = false;
                if (priorityFilter && priority !== priorityFilter) show = false;
                if (projectFilter && project !== projectFilter) show = false;
                if (searchInput && !title.includes(searchInput)) show = false;
                
                if (show) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Update task counts for each column after filtering
            const kanbanColumns = document.querySelectorAll('.kanban-column');
            kanbanColumns.forEach(column => {
                const visibleTasks = column.querySelectorAll('.task-card[style*="block"], .task-card:not([style*="none"])');
                const countElement = column.querySelector('.task-count');
                if (countElement) {
                    countElement.textContent = visibleTasks.length;
                }
            });
        }
        
        // Add event listeners
        document.getElementById('status-filter').addEventListener('change', filterTasks);
        document.getElementById('priority-filter').addEventListener('change', filterTasks);
        document.getElementById('project-filter').addEventListener('change', filterTasks);
        document.getElementById('search-input').addEventListener('input', filterTasks);
        
        // Initialize Kanban board organization on page load
        document.addEventListener('DOMContentLoaded', function() {
            organizeKanbanBoard();
        });
    </script>
    
    <!-- Task Details Modal -->
    <div id="taskModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Task Details</h2>
                <button class="modal-close" onclick="closeTaskModal()">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Task details will be loaded here -->
            </div>
        </div>
    </div>
    
    <style>
        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 1400px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
            display: flex;
            flex-direction: column;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        
        .modal-breadcrumb {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
        }
        
        .modal-title-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .modal-title {
            font-size: 24px;
            font-weight: 600;
            color: white;
            margin: 0;
        }
        
        .modal-tags {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .modal-tag {
            background: #e5e7eb;
            color: #374151;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .modal-tag.status {
            background: #dcfce7;
            color: #166534;
        }
        
        .modal-tag.priority {
            background: #fef3c7;
            color: #92400e;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .modal-body {
            padding: 20px;
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
        
        
        /* Task Details Styles */
        .task-detail-section {
            margin-bottom: 16px;
        }
        
        .task-detail-section:last-child {
            margin-bottom: 0;
        }
        
        .task-detail-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .task-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }
        
        .task-detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .task-detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .task-detail-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }
        
        .task-detail-description {
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            font-size: 14px;
            line-height: 1.6;
            color: #374151;
        }
        
        .task-detail-assigned {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .task-detail-tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .task-detail-tag.status-todo {
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .task-detail-tag.status-in_progress {
            background: #dbeafe;
            color: #1d4ed8;
        }
        
        .task-detail-tag.status-review {
            background: #fef3c7;
            color: #d97706;
        }
        
        .task-detail-tag.status-completed {
            background: #d1fae5;
            color: #059669;
        }
        
        .task-detail-tag.priority-low {
            background: #f0fdf4;
            color: #16a34a;
        }
        
        .task-detail-tag.priority-medium {
            background: #fef3c7;
            color: #d97706;
        }
        
        .task-detail-tag.priority-high {
            background: #fed7aa;
            color: #ea580c;
        }
        
        .task-detail-tag.priority-urgent {
            background: #fecaca;
            color: #dc2626;
        }
        
        .status-change-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .status-dropdown {
            padding: 8px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        
        .status-dropdown:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .status-dropdown:hover {
            border-color: #9ca3af;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .task-detail-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }
        
        .task-detail-avatar.bg-purple { background: #8b5cf6; }
        .task-detail-avatar.bg-orange { background: #f97316; }
        .task-detail-avatar.bg-green { background: #10b981; }
        .task-detail-avatar.bg-blue { background: #3b82f6; }
        .task-detail-avatar.bg-pink { background: #ec4899; }
        .task-detail-avatar.bg-indigo { background: #6366f1; }
        
        .task-detail-project {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .task-detail-project svg {
            width: 16px;
            height: 16px;
            color: #6b7280;
        }
        
        .task-detail-timeline {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .task-detail-timeline-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .task-detail-timeline-item svg {
            width: 16px;
            height: 16px;
            color: #6b7280;
        }
        
        .task-detail-timeline-item.overdue {
            color: #dc2626;
        }
        
        .task-detail-timeline-item.overdue svg {
            color: #dc2626;
        }
        
        /* Activity Feed Style Comments Section - Matching Project Modal */
        .comments-section {
            padding: 24px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .comments-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }
        
        .add-comment-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }
        
        .add-comment-btn:hover {
            background: #5a67d8;
        }
        
        .comments-list {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        
        .comment-item {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            position: relative;
        }
        
        .comment-item:last-child {
            margin-bottom: 0;
        }
        
        .comment-bullet {
            width: 8px;
            height: 8px;
            background: #667eea;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }
        
        .comment-content-wrapper {
            flex: 1;
            background: white;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .comment-text {
            font-size: 14px;
            color: #1f2937;
            line-height: 1.4;
            margin-bottom: 8px;
        }
        
        .comment-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #6b7280;
        }
        
        .comment-author {
            font-weight: 500;
            color: #667eea;
        }
        
        .comment-date {
            color: #9ca3af;
        }
        
        .comment-form {
            display: block;
            margin-top: 16px;
        }
        
        .comment-form-wrapper {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }
        
        .comment-textarea {
            flex: 1;
            min-height: 40px;
            max-height: 80px;
            border: none;
            outline: none;
            resize: vertical;
            font-size: 14px;
            font-family: inherit;
            color: #1f2937;
            background: transparent;
        }
        
        .comment-textarea:focus {
            outline: none;
        }
        
        .comment-textarea::placeholder {
            color: #9ca3af;
        }
        
        .comment-send-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
            height: fit-content;
            transition: all 0.2s;
        }
        
        .comment-send-btn:hover {
            background: #5a67d8;
        }
        
        .no-comments {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
            font-size: 14px;
        }
        
        .no-comments::before {
            content: "💬";
            display: block;
            font-size: 32px;
            margin-bottom: 12px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .modal {
                padding: 10px;
            }
            
            .modal-content {
                max-width: 95vw;
                max-height: 95vh;
            }
            
            .modal-header {
                padding: 20px;
            }
            
            .modal-body {
                padding: 16px;
                flex-direction: column;
                gap: 16px;
            }
            
            .modal-content-left,
            .modal-content-right {
                flex: none;
                min-width: auto;
            }
            
            
            .task-detail-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }
        
        @media (max-width: 1024px) {
            .task-detail-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
    
    <script>
        // Task data for modal - will be populated by PHP
        const taskData = {};
    </script>

    <script>
        // Populate task data
        // @ts-nocheck
        @foreach($tasks as $task)
        taskData[{{ $task->id }}] = {
            id: {{ $task->id }},
            title: {!! json_encode($task->title) !!},
            description: {!! json_encode($task->description ?? '') !!},
            status: {!! json_encode($task->status) !!},
            priority: {!! json_encode($task->priority) !!},
            project: {!! json_encode($task->project->name ?? 'No Project') !!},
            created_by: {!! json_encode($task->createdBy->name ?? 'Unknown') !!},
            assigned_users: [
                @foreach($task->assignedUsers as $user)
                {
                    id: {{ $user->id }},
                    name: {!! json_encode($user->name) !!},
                    initial: {!! json_encode(strtoupper(substr($user->name, 0, 1))) !!},
                    profile_picture: {!! json_encode($user->profile_picture) !!}
                }{{ $loop->last ? '' : ',' }}
                @endforeach
            ],
            due_date: {!! json_encode($task->due_date ? $task->due_date->format('M j, Y') : 'No due date') !!},
            estimated_hours: {!! json_encode($task->estimated_hours ?? 'Not estimated') !!},
            actual_hours: {!! json_encode($task->actual_hours ?? 'Not tracked') !!},
            created_at: {!! json_encode($task->created_at ? $task->created_at->format('M j, Y') : 'Unknown') !!},
            updated_at: {!! json_encode($task->updated_at ? $task->updated_at->format('M j, Y') : 'Unknown') !!}
        };
        @endforeach
    </script>

    <script>
        
        function openTaskModal(taskId) {
            const task = taskData[taskId];
            if (!task) return;
            
            const modal = document.getElementById('taskModal');
            const modalBody = document.getElementById('modalBody');
            
            // Generate modal content with two-column layout
            modalBody.innerHTML = `
                <div class="modal-content-left">
                    <div class="task-detail-section">
                    <h3 class="task-detail-title">Basic Information</h3>
                    <div class="task-detail-grid">
                        <div class="task-detail-item">
                            <div class="task-detail-label">Status</div>
                            <div class="status-change-container">
                                <select class="status-dropdown" onchange="changeTaskStatus(${task.id}, this.value)">
                                    <option value="todo" ${task.status === 'todo' ? 'selected' : ''}>To Do</option>
                                    <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                    <option value="review" ${task.status === 'review' ? 'selected' : ''}>Review</option>
                                    <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completed</option>
                                </select>
                                <div class="task-detail-tag status-${task.status}" id="status-tag-${task.id}">${task.status.replace('_', ' ')}</div>
                            </div>
                        </div>
                        <div class="task-detail-item">
                            <div class="task-detail-label">Priority</div>
                            <div class="task-detail-tag priority-${task.priority}">${task.priority}</div>
                        </div>
                    </div>
                </div>
                
                <div class="task-detail-section">
                    <h3 class="task-detail-title">Description</h3>
                    <div class="task-detail-description">
                        ${task.description || 'No description provided'}
                    </div>
                </div>
                
                <div class="task-detail-section">
                    <h3 class="task-detail-title">Project & Team</h3>
                    <div class="task-detail-grid">
                        <div class="task-detail-item">
                            <div class="task-detail-label">Project</div>
                            <div class="task-detail-project">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                ${task.project}
                            </div>
                        </div>
                        <div class="task-detail-item">
                            <div class="task-detail-label">Created By</div>
                            <div class="task-detail-value">${task.created_by}</div>
                        </div>
                    </div>
                </div>
                
                <div class="task-detail-section">
                    <h3 class="task-detail-title">Assigned Team Members</h3>
                    <div class="task-detail-assigned">
                        ${task.assigned_users.length > 0 ? 
                            task.assigned_users.map((user, index) => {
                                const colors = ['bg-purple', 'bg-orange', 'bg-green', 'bg-blue', 'bg-pink', 'bg-indigo'];
                                const colorClass = colors[index % colors.length];
                                return `
                                    <div class="task-detail-avatar ${colorClass}" title="${user.name}">
                                        ${user.initial}
                                    </div>
                                `;
                            }).join('') : 
                            '<div class="task-detail-value" style="color: #6b7280; font-style: italic;">No team members assigned</div>'
                        }
                    </div>
                </div>
                
                <div class="task-detail-section">
                    <h3 class="task-detail-title">Timeline & Effort</h3>
                    <div class="task-detail-timeline">
                        <div class="task-detail-timeline-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span><strong>Due Date:</strong> ${task.due_date}</span>
                        </div>
                        <div class="task-detail-timeline-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong>Estimated Hours:</strong> ${task.estimated_hours}</span>
                        </div>
                        <div class="task-detail-timeline-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong>Actual Hours:</strong> ${task.actual_hours}</span>
                        </div>
                        <div class="task-detail-timeline-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span><strong>Created:</strong> ${task.created_at}</span>
                        </div>
                        <div class="task-detail-timeline-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span><strong>Last Updated:</strong> ${task.updated_at}</span>
                        </div>
                    </div>
                    </div>
                </div>
                
                <div class="modal-content-right">
                    <div class="comments-section">
                        <div class="comments-header">
                            <h3 class="comments-title">Activity</h3>
                        </div>
                        
                        <div class="comments-list" id="comments-list-${task.id}">
                            <div class="no-comments">No comments yet. Be the first to comment!</div>
                        </div>
                        
                        <div class="comment-form" id="comment-form-${task.id}" style="display: block;">
                            <div class="comment-form-wrapper">
                                <textarea class="comment-textarea" placeholder="Write a comment..." id="comment-text-${task.id}"></textarea>
                                <button class="comment-send-btn" onclick="addComment(${task.id})">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Show modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Load existing comments
            loadComments(taskId);
        }
        
        function closeTaskModal() {
            const modal = document.getElementById('taskModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside
        document.getElementById('taskModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTaskModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTaskModal();
            }
        });
        
        // Function to change task status
        function changeTaskStatus(taskId, newStatus) {
            // Update the task data
            if (taskData[taskId]) {
                taskData[taskId].status = newStatus;
            }
            
            // Update the status tag in the modal
            const statusTag = document.getElementById(`status-tag-${taskId}`);
            if (statusTag) {
                statusTag.className = `task-detail-tag status-${newStatus}`;
                statusTag.textContent = newStatus.replace('_', ' ');
            }
            
            // Update the status tag in the task card
            const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
            if (taskCard) {
                const cardStatusTag = taskCard.querySelector('.task-status');
                if (cardStatusTag) {
                    cardStatusTag.className = `task-status status-${newStatus.replace(' ', '-')}`;
                    cardStatusTag.textContent = newStatus.replace('_', ' ').toUpperCase();
                }
            }
            
            // Send AJAX request to update the status on the server
            fetch(`/admin/custom-tasks/${taskId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the task card's data-status attribute
                    const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
                    if (taskCard) {
                        taskCard.dataset.status = newStatus;
                    }
                    
                    // Reorganize the Kanban board to move the task to the correct column
                    organizeKanbanBoard();
                    
                    // Show success message
                    showStatusUpdateMessage('Status updated successfully!');
                } else {
                    // Show error message
                    showStatusUpdateMessage('Failed to update status. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatusUpdateMessage('Failed to update status. Please try again.', 'error');
            });
        }
        
        // Function to show status update messages
        function showStatusUpdateMessage(message, type = 'success') {
            const messageDiv = document.createElement('div');
            messageDiv.className = `status-message ${type}`;
            messageDiv.textContent = message;
            messageDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 3000;
                animation: slideInRight 0.3s ease-out;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
            `;
            
            document.body.appendChild(messageDiv);
            
            // Remove message after 3 seconds
            setTimeout(() => {
                messageDiv.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => {
                    document.body.removeChild(messageDiv);
                }, 300);
            }, 3000);
        }
        
        // Comment functions
        
        function loadComments(taskId) {
            const commentsList = document.getElementById(`comments-list-${taskId}`);
            if (!commentsList) return;
            
            console.log('Loading comments for task:', taskId);
            
            fetch(`/admin/custom-tasks/${taskId}/comments`, {
                credentials: 'same-origin'
            })
                .then(response => {
                    console.log('Load comments response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(comments => {
                    if (comments.length === 0) {
                        commentsList.innerHTML = '<div class="no-comments">No comments yet. Be the first to comment!</div>';
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
                    commentsList.innerHTML = '<div class="no-comments">Error loading comments.</div>';
                });
        }
        
        function addComment(taskId) {
            const textarea = document.getElementById(`comment-text-${taskId}`);
            const commentsList = document.getElementById(`comments-list-${taskId}`);
            
            if (!textarea || !commentsList) return;
            
            const commentText = textarea.value.trim();
            if (!commentText) return;
            
            // Get CSRF token from cookie
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                return '';
            }
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            console.log('Task ID:', taskId);
            console.log('Comment text:', commentText);
            console.log('CSRF Token:', csrfToken);
            
            // Use FormData instead of JSON for better Laravel compatibility
            const formData = new FormData();
            formData.append('content', commentText);
            formData.append('_token', csrfToken);
            
            // Save comment to database
            fetch(`/admin/custom-tasks/${taskId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Create comment HTML with Activity feed style
                const commentHtml = `
                    <div class="comment-item">
                        <div class="comment-bullet"></div>
                        <div class="comment-content-wrapper">
                            <div class="comment-text">${data.comment.content}</div>
                            <div class="comment-meta">
                                <span class="comment-author">${data.comment.user_name}</span>
                                <span class="comment-date">${data.comment.created_at}</span>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove "no comments" message if it exists
                const noComments = commentsList.querySelector('.no-comments');
                if (noComments) {
                    noComments.remove();
                }
                
                // Add new comment to the bottom
                commentsList.insertAdjacentHTML('beforeend', commentHtml);
                
                            // Clear form
            textarea.value = '';
                
                // Show success message
                showStatusUpdateMessage('Comment added successfully!');
            })
            .catch(error => {
                console.error('Error adding comment:', error);
                showStatusUpdateMessage('Error adding comment: ' + (error.message || 'Please try again.'));
            });
        }

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
</body>
</html>
