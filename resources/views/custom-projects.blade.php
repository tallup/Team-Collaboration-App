<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Projects - USGamNeeds Team Collaboration</title>
    <!-- Updated: {{ now() }} - Profile Pictures Fix v2 -->
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
            color: white;
            opacity: 1;
        }
        
        /* Projects Grid */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .project-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .project-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        
        .project-title {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .project-description {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .project-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .project-owner {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        .owner-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        
        .owner-info {
            flex: 1;
        }
        
        .owner-name {
            font-weight: 500;
            color: #1f2937;
            font-size: 14px;
        }
        
        .owner-role {
            font-size: 12px;
            color: #6b7280;
        }
        
        .project-team {
            margin-bottom: 16px;
        }
        
        .team-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .team-members {
            display: flex;
            gap: 8px;
        }
        
        .team-member {
            width: 32px;
            height: 32px;
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
        
        .member-1 { background: #8b5cf6; }
        .member-2 { background: #f59e0b; }
        .member-3 { background: #10b981; }
        .member-4 { background: #ef4444; }
        
        .project-progress {
            margin-bottom: 16px;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .progress-text {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
        }
        
        .progress-percentage {
            font-size: 14px;
            font-weight: 600;
            color: #667eea;
        }
        
        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        .project-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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
        
        /* Create Project Button */
        .create-project-btn {
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
        
        .create-project-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .create-project-btn svg {
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
            
            .projects-grid {
                grid-template-columns: 1fr;
            }
            
            .project-stats {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }
        
        /* Project Modal Styles */
        .project-modal {
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
            padding: 20px;
        }
        
        .project-modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 1200px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .project-modal-header {
            padding: 24px 32px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .project-modal-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        
        .project-modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            transition: all 0.2s;
        }
        
        .project-modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .project-modal-body {
            display: flex;
            flex: 1;
            overflow: hidden;
        }
        
        .project-modal-content-left {
            flex: 2;
            padding: 32px;
            overflow-y: auto;
        }
        
        .project-modal-content-right {
            flex: 1;
            border-left: 1px solid #e5e7eb;
            background: #f8fafc;
        }
        
        .project-details-section {
            margin-bottom: 32px;
        }
        
        .project-details-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .project-details-section h3 svg {
            width: 16px;
            height: 16px;
            color: #667eea;
        }
        
        /* Ensure all modal icons are properly sized */
        .project-modal svg {
            max-width: 20px;
            max-height: 20px;
        }
        
        .project-details-section svg,
        .project-team-section svg,
        .project-tasks-section svg {
            width: 16px !important;
            height: 16px !important;
        }
        
        .project-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .project-info-item {
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .project-info-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .project-info-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }
        
        .project-team-section {
            margin-bottom: 32px;
        }
        
        .project-team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }
        
        .project-team-member {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        
        .project-team-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        
        .project-team-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 2px 0;
        }
        
        .project-team-info p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }
        
        .project-tasks-section {
            margin-bottom: 32px;
        }
        
        .project-tasks-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .project-task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .project-task-item:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
        }
        
        .project-task-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 4px 0;
        }
        
        .project-task-info p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }
        
        .project-task-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        /* Activity Feed Styles */
        .activity-section {
            padding: 24px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .activity-title {
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
        
        .activity-list {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        
        .activity-item {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            position: relative;
        }
        
        .activity-item:last-child {
            margin-bottom: 0;
        }
        
        .activity-bullet {
            width: 8px;
            height: 8px;
            background: #667eea;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }
        
        .activity-content-wrapper {
            flex: 1;
            background: white;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .activity-text {
            font-size: 14px;
            color: #1f2937;
            line-height: 1.4;
            margin-bottom: 8px;
        }
        
        .activity-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #6b7280;
        }
        
        .activity-author {
            font-weight: 500;
            color: #667eea;
        }
        
        .activity-date {
            color: #9ca3af;
        }
        
        .activity-form {
            display: block;
            margin-top: 16px;
        }
        
        .activity-form-wrapper {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }
        
        .activity-textarea {
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
        
        .activity-textarea:focus {
            outline: none;
        }
        
        .activity-textarea::placeholder {
            color: #9ca3af;
        }
        
        .activity-send-btn {
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
        
        .activity-send-btn:hover {
            background: #5a67d8;
        }
        
        .no-activity {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
            font-size: 14px;
        }
        
        .no-activity::before {
            content: "💬";
            display: block;
            font-size: 32px;
            margin-bottom: 12px;
        }
        
        @media (max-width: 768px) {
            .project-modal-content {
                width: 95%;
                max-height: 95vh;
            }
            
            .project-modal-body {
                flex-direction: column;
            }
            
            .project-modal-content-right {
                border-left: none;
                border-top: 1px solid #e5e7eb;
            }
            
            .project-info-grid {
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
            <a href="/admin/custom-projects" class="nav-item active">
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
                Projects Overview
            </div>
            <div class="page-subtitle">Manage and track all your team projects</div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Project::count() }}</div>
                    <div class="stat-label">Total Projects</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Project::where('status', 'active')->count() }}</div>
                    <div class="stat-label">Active Projects</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Project::where('status', 'completed')->count() }}</div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ \App\Models\Project::where('status', 'pending')->count() }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>
        
        <!-- Projects Grid -->
        <div class="projects-grid">
            @forelse($projects as $project)
                @php
                    $totalTasks = $project->tasks->count();
                    $completedTasks = $project->tasks->where('status', 'completed')->count();
                    $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    $teamMembers = $project->users->take(4);
                @endphp
                
                <div class="project-card" onclick="openProjectModal({{ $project->id }})" style="cursor: pointer;">
                    <div class="project-header">
                        <div>
                            <h3 class="project-title">{{ $project->name }}</h3>
                            <p class="project-description">{{ $project->description ?? 'No description available' }}</p>
                        </div>
                        <div class="project-status status-{{ $project->status }}">
                            {{ ucfirst($project->status) }}
                        </div>
                    </div>
                    
                    <div class="project-owner">
                        <div class="owner-avatar">
                            {{ substr($project->owner->name ?? $project->primaryUser->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="owner-info">
                            <div class="owner-name">{{ $project->owner->name ?? $project->primaryUser->name ?? 'Unknown Owner' }}</div>
                            <div class="owner-role">Project Owner</div>
                        </div>
                    </div>
                    
                    @if($teamMembers->count() > 0)
                        <div class="project-team">
                            <div class="team-label">Team Members</div>
                            <div class="team-members">
                                @foreach($teamMembers as $index => $member)
                                    <div class="team-member member-{{ ($index % 4) + 1 }}">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="project-progress">
                        <div class="progress-label">
                            <span class="progress-text">Progress</span>
                            <span class="progress-percentage">{{ $progress }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progress }}%;"></div>
                        </div>
                    </div>
                    
                    <div class="project-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $totalTasks }}</div>
                            <div class="stat-label">Total Tasks</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $completedTasks }}</div>
                            <div class="stat-label">Completed</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $totalTasks - $completedTasks }}</div>
                            <div class="stat-label">In Progress</div>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="/admin/projects/{{ $project->id }}/edit" class="btn btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <a href="/admin/tasks?project={{ $project->id }}" class="btn btn-secondary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            View Tasks
                        </a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #6b7280;">
                    <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; opacity: 0.5;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 style="font-size: 18px; margin-bottom: 8px;">No projects found</h3>
                    <p style="font-size: 14px; margin-bottom: 24px;">Get started by creating your first project</p>
                    <a href="/admin/custom-projects/create" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Project
                    </a>
                </div>
            @endforelse
        </div>
    </main>
    
    <!-- Project Modal -->
    <div id="projectModal" class="project-modal">
        <div class="project-modal-content">
            <div class="project-modal-header">
                <h2 class="project-modal-title" id="projectModalTitle">Project Details</h2>
                <button class="project-modal-close" onclick="closeProjectModal()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="project-modal-body">
                <div class="project-modal-content-left">
                    <!-- Project Details Section -->
                    <div class="project-details-section">
                        <h3>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Project Information
                        </h3>
                        <div class="project-info-grid" id="projectInfoGrid">
                            <!-- Project info will be populated here -->
                        </div>
                    </div>
                    
                    <!-- Team Section -->
                    <div class="project-team-section">
                        <h3>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Team Members
                        </h3>
                        <div class="project-team-grid" id="projectTeamGrid">
                            <!-- Team members will be populated here -->
                        </div>
                    </div>
                    
                    <!-- Tasks Section -->
                    <div class="project-tasks-section">
                        <h3>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Project Tasks
                        </h3>
                        <div class="project-tasks-list" id="projectTasksList">
                            <!-- Tasks will be populated here -->
                        </div>
                    </div>
                </div>
                
                <div class="project-modal-content-right">
                    <div class="activity-section">
                        <div class="activity-header">
                            <h3 class="activity-title">Activity</h3>
                        </div>
                        <div class="activity-list" id="projectActivityList">
                            <div class="no-activity">No comments yet. Be the first to comment!</div>
                        </div>
                        <div class="activity-form" id="projectCommentForm" style="display: block;">
                            <div class="activity-form-wrapper">
                                <textarea class="activity-textarea" placeholder="Write a comment..." id="projectCommentText"></textarea>
                                <button class="activity-send-btn" onclick="addProjectComment()">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Create Button -->
    <a href="/admin/custom-projects/create" class="create-project-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
    
    <script>
        // Project data from Laravel
        // @ts-nocheck
        const projectData = {!! json_encode($projects->map(function($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'status' => $project->status,
                'owner' => $project->owner ? $project->owner->name : ($project->primaryUser ? $project->primaryUser->name : 'Unknown'),
                'created_at' => $project->created_at ? $project->created_at->format('M j, Y') : 'Unknown',
                'team_members' => $project->users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email ?? 'No email'
                    ];
                }),
                'tasks' => $project->tasks->map(function($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'description' => $task->description,
                        'status' => $task->status,
                        'priority' => $task->priority,
                        'due_date' => $task->due_date ? $task->due_date->format('M j, Y') : 'No due date',
                        'assigned_users' => $task->assignedUsers->map(function($user) {
                            return $user->name;
                        })
                    ];
                })
            ];
        })) !!};
        
        function openProjectModal(projectId) {
            const project = projectData.find(p => p.id === projectId);
            if (!project) return;
            
            const modal = document.getElementById('projectModal');
            const title = document.getElementById('projectModalTitle');
            const infoGrid = document.getElementById('projectInfoGrid');
            const teamGrid = document.getElementById('projectTeamGrid');
            const tasksList = document.getElementById('projectTasksList');
            
            // Set title
            title.textContent = project.name;
            
            // Populate project info
            infoGrid.innerHTML = `
                <div class="project-info-item">
                    <div class="project-info-label">Status</div>
                    <div class="project-info-value">${project.status}</div>
                </div>
                <div class="project-info-item">
                    <div class="project-info-label">Owner</div>
                    <div class="project-info-value">${project.owner}</div>
                </div>
                <div class="project-info-item">
                    <div class="project-info-label">Created</div>
                    <div class="project-info-value">${project.created_at}</div>
                </div>
                <div class="project-info-item">
                    <div class="project-info-label">Description</div>
                    <div class="project-info-value">${project.description || 'No description available'}</div>
                </div>
            `;
            
            // Populate team members
            if (project.team_members.length > 0) {
                teamGrid.innerHTML = project.team_members.map(member => `
                    <div class="project-team-member">
                        <div class="project-team-avatar">${member.name.charAt(0).toUpperCase()}</div>
                        <div class="project-team-info">
                            <h4>${member.name}</h4>
                            <p>${member.email}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                teamGrid.innerHTML = '<p style="color: #6b7280; text-align: center; padding: 20px;">No team members assigned</p>';
            }
            
            // Populate tasks
            if (project.tasks.length > 0) {
                tasksList.innerHTML = project.tasks.map(task => `
                    <div class="project-task-item">
                        <div class="project-task-info">
                            <h4>${task.title}</h4>
                            <p>${task.description || 'No description'}</p>
                        </div>
                        <div class="project-task-status status-${task.status.toLowerCase().replace(' ', '-')}">
                            ${task.status}
                        </div>
                    </div>
                `).join('');
            } else {
                tasksList.innerHTML = '<p style="color: #6b7280; text-align: center; padding: 20px;">No tasks assigned to this project</p>';
            }
            
            // Store project ID in modal dataset
            modal.dataset.projectId = projectId;
            
            // Show modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Load existing comments
            loadProjectComments(projectId);
        }
        
        function closeProjectModal() {
            const modal = document.getElementById('projectModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        
        function loadProjectComments(projectId) {
            const activityList = document.getElementById('projectActivityList');
            if (!activityList) return;
            
            console.log('Loading comments for project:', projectId);
            
            fetch(`/admin/custom-projects/${projectId}/comments`, {
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
                        activityList.innerHTML = '<div class="no-activity">No comments yet. Be the first to comment!</div>';
                    } else {
                        activityList.innerHTML = comments.map(comment => `
                            <div class="activity-item">
                                <div class="activity-bullet"></div>
                                <div class="activity-content-wrapper">
                                    <div class="activity-text">${comment.content}</div>
                                    <div class="activity-meta">
                                        <span class="activity-author">${comment.user_name}</span>
                                        <span class="activity-date">${comment.created_at}</span>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    activityList.innerHTML = '<div class="no-activity">Error loading comments</div>';
                });
        }
        
        function addProjectComment() {
            const textarea = document.getElementById('projectCommentText');
            const activityList = document.getElementById('projectActivityList');
            
            if (!textarea || !activityList) return;
            
            const commentText = textarea.value.trim();
            if (!commentText) return;
            
            // Get current project ID from the modal
            const modal = document.getElementById('projectModal');
            const projectId = modal.dataset.projectId;
            
            console.log('Project ID:', projectId);
            console.log('Comment text:', commentText);
            
            if (!projectId) {
                console.error('No project ID found');
                return;
            }
            
            // Get CSRF token from cookie
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                return '';
            }
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            console.log('CSRF Token:', csrfToken);
            
            // Use FormData instead of JSON for better Laravel compatibility
            const formData = new FormData();
            formData.append('content', commentText);
            formData.append('_token', csrfToken);
            
            // Send comment to backend
            fetch(`/admin/custom-projects/${projectId}/comments`, {
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
                if (data.comment) {
                    // Remove "no comments" message if it exists
                    const noComments = activityList.querySelector('.no-activity');
                    if (noComments) {
                        noComments.remove();
                    }
                    
                    // Add new comment to the bottom
                    const commentHtml = `
                        <div class="activity-item">
                            <div class="activity-bullet"></div>
                            <div class="activity-content-wrapper">
                                <div class="activity-text">${data.comment.content}</div>
                                <div class="activity-meta">
                                    <span class="activity-author">${data.comment.user_name}</span>
                                    <span class="activity-date">${data.comment.created_at}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    activityList.insertAdjacentHTML('beforeend', commentHtml);
                    
                                    // Clear form
                textarea.value = '';
                }
            })
            .catch(error => {
                console.error('Error adding comment:', error);
                alert('Error adding comment: ' + (error.message || 'Please try again.'));
            });
        }
        
        // Close modal when clicking outside
        document.getElementById('projectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProjectModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProjectModal();
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
</body>
</html>
