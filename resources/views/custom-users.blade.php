<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Team Members - USGamNeeds Team Collaboration</title>
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
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn svg {
            width: 16px;
            height: 16px;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-icon.purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-icon.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .stat-title {
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
        }
        
        /* User Cards */
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
        }
        
        .user-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            text-decoration: none;
            color: inherit;
        }
        
        .user-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e5e7eb;
        }
        
        .user-info h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }
        
        .user-info p {
            font-size: 14px;
            color: #6b7280;
        }
        
        .user-role {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .user-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #6b7280;
        }
        
        .user-detail svg {
            width: 16px;
            height: 16px;
        }
        
        .user-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        
        .hidden {
            display: none !important;
        }
        
        /* User Modal Styles */
        .user-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .user-modal.show {
            display: flex;
        }
        
        .user-modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalSlideIn 0.3s ease-out;
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
        
        .user-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px 16px 0 0;
            margin-bottom: 0;
        }
        
        .user-modal-title {
            font-size: 20px;
            font-weight: 600;
            color: white;
            margin: 0;
        }
        
        .user-modal-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            color: white;
            transition: all 0.2s;
        }
        
        .user-modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .user-modal-close svg {
            width: 20px;
            height: 20px;
        }
        
        .user-modal-body {
            padding: 24px;
        }
        
        .user-modal-profile {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .user-modal-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 28px;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 3px solid #e5e7eb;
        }
        
        .user-modal-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .user-modal-info h3 {
            font-size: 28px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 8px 0;
        }
        
        .user-modal-info p {
            font-size: 18px;
            color: #6b7280;
            margin: 0 0 16px 0;
        }
        
        .user-modal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .user-modal-badge.admin {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .user-modal-badge.member {
            background: #f3f4f6;
            color: #374151;
        }
        
        .user-modal-badge svg {
            width: 12px;
            height: 12px;
        }
        
        .user-modal-details {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .user-modal-detail-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        
        .user-modal-detail-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }
        
        .user-modal-detail-icon svg {
            width: 18px;
            height: 18px;
        }
        
        .user-modal-detail-content {
            flex: 1;
        }
        
        .user-modal-detail-label {
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .user-modal-detail-value {
            font-size: 16px;
            font-weight: 500;
            color: #1f2937;
        }
        
        .user-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 24px;
            border-top: 1px solid #e5e7eb;
            margin-top: 24px;
        }
        
        .user-modal-footer .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        
        .user-modal-footer .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }
        
        .user-modal-footer .btn-secondary:hover {
            background: #e5e7eb;
            color: #374151;
        }
        
        .user-modal-footer .btn-primary {
            background: #667eea;
            color: white;
            border: 1px solid #667eea;
        }
        
        .user-modal-footer .btn-primary:hover {
            background: #5a67d8;
            border-color: #5a67d8;
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
            <a href="/admin/custom-tasks" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>Tasks</span>
            </a>
            <a href="/admin/custom-users" class="nav-item active">
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
        <!-- Page Header -->
        <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="page-title">Team Members</h1>
                    <p class="page-subtitle">Manage your team members and their access permissions</p>
                </div>
                <a href="/admin/custom-users/create" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Member
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon purple">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-title">Total Members</div>
                        <div class="stat-value">{{ $users->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-title">Active Members</div>
                        <div class="stat-value">{{ $users->where('is_admin', false)->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon purple">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-title">Administrators</div>
                        <div class="stat-value">{{ $users->where('is_admin', true)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Users Grid -->
        <div class="users-grid" id="usersGrid">
        @foreach($users as $user)
            <div class="user-card" 
                 data-name="{{ strtolower($user->name) }}" 
                 data-email="{{ strtolower($user->email) }}" 
                 data-position="{{ strtolower($user->position ?? '') }}" 
                 data-role="{{ $user->is_admin ? 'admin' : 'member' }}"
                 onclick="openUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->position ?? 'No position set') }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->phone ?? '') }}', '{{ $user->created_at->format('M j, Y') }}', {{ $user->is_admin ? 'true' : 'false' }}, '{{ addslashes($user->profile_picture ?? '') }}')">
                
                <!-- User Header -->
                <div class="user-header">
                    @if($user->profile_picture)
                        <img class="user-avatar" src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}">
                    @else
                        <div class="user-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 18px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="user-info">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->position ?? 'No position set' }}</p>
                    </div>
                </div>
                
                <!-- User Role -->
                <div class="user-role">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $user->is_admin ? 'Admin' : 'Member' }}
                </div>
                
                <!-- User Details -->
                <div class="user-details">
                    <div class="user-detail">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $user->email }}
                    </div>
                    
                    @if($user->phone)
                    <div class="user-detail">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $user->phone }}
                    </div>
                    @endif
                    
                    <div class="user-detail">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Joined {{ $user->created_at->format('M j, Y') }}
                    </div>
                </div>
                
            </div>
        @endforeach
        </div>
    </main>

    <!-- User Details Modal -->
    <div id="userModal" class="user-modal">
        <div class="user-modal-content">
            <div class="user-modal-header">
                <h2 class="user-modal-title">User Details</h2>
                <button class="user-modal-close" onclick="closeUserModal()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="user-modal-body">
                <div class="user-modal-profile">
                    <div class="user-modal-avatar" id="modalUserAvatar">
                        <!-- Avatar will be populated by JavaScript -->
                    </div>
                    <div class="user-modal-info">
                        <h3 id="modalUserName">User Name</h3>
                        <p id="modalUserPosition">Position</p>
                        <div class="user-modal-badge" id="modalUserBadge">
                            <!-- Badge will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <div class="user-modal-details">
                    <div class="user-modal-detail-item">
                        <div class="user-modal-detail-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="user-modal-detail-content">
                            <div class="user-modal-detail-label">Email</div>
                            <div class="user-modal-detail-value" id="modalUserEmail">email@example.com</div>
                        </div>
                    </div>
                    
                    <div class="user-modal-detail-item" id="modalUserPhoneItem" style="display: none;">
                        <div class="user-modal-detail-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div class="user-modal-detail-content">
                            <div class="user-modal-detail-label">Phone</div>
                            <div class="user-modal-detail-value" id="modalUserPhone">+1234567890</div>
                        </div>
                    </div>
                    
                    <div class="user-modal-detail-item">
                        <div class="user-modal-detail-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="user-modal-detail-content">
                            <div class="user-modal-detail-label">Joined</div>
                            <div class="user-modal-detail-value" id="modalUserJoined">Jan 1, 2024</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="user-modal-footer">
                <button class="btn btn-secondary" onclick="closeUserModal()">Close</button>
                <a href="/admin/profile" class="btn btn-primary">View Profile</a>
            </div>
        </div>
    </div>

    <script>
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

        // User Modal Functions
        function openUserModal(userId, name, position, email, phone, joined, isAdmin, profilePicture) {
            const modal = document.getElementById('userModal');
            const modalAvatar = document.getElementById('modalUserAvatar');
            const modalName = document.getElementById('modalUserName');
            const modalPosition = document.getElementById('modalUserPosition');
            const modalEmail = document.getElementById('modalUserEmail');
            const modalPhone = document.getElementById('modalUserPhone');
            const modalPhoneItem = document.getElementById('modalUserPhoneItem');
            const modalJoined = document.getElementById('modalUserJoined');
            const modalBadge = document.getElementById('modalUserBadge');
            
            // Set user information
            modalName.textContent = name;
            modalPosition.textContent = position;
            modalEmail.textContent = email;
            modalJoined.textContent = joined;
            
            // Set avatar
            if (profilePicture && profilePicture.trim() !== '') {
                modalAvatar.innerHTML = `<img src="/${profilePicture}" alt="${name}">`;
            } else {
                modalAvatar.textContent = name.charAt(0).toUpperCase();
            }
            
            // Set phone (show/hide based on availability)
            if (phone && phone.trim() !== '') {
                modalPhone.textContent = phone;
                modalPhoneItem.style.display = 'flex';
            } else {
                modalPhoneItem.style.display = 'none';
            }
            
            // Set badge
            if (isAdmin) {
                modalBadge.className = 'user-modal-badge admin';
                modalBadge.innerHTML = `
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Admin
                `;
            } else {
                modalBadge.className = 'user-modal-badge member';
                modalBadge.innerHTML = `
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Member
                `;
            }
            
            // Show modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        function closeUserModal() {
            const modal = document.getElementById('userModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('userModal');
            if (e.target === modal) {
                closeUserModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeUserModal();
            }
        });
    </script>
</body>
</html>


