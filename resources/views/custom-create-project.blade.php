<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project - USGamNeeds Team Collaboration</title>
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
        
        /* Checkbox-based user selection */
        .user-checkbox-container {
            margin-bottom: 20px;
        }
        
        .user-checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            max-height: 300px;
            overflow-y: auto;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: #fafafa;
        }
        
        .user-checkbox-item {
            display: flex;
            align-items: center;
        }
        
        .user-checkbox {
            display: none;
        }
        
        .user-checkbox-label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }
        
        .user-checkbox-label:hover {
            border-color: #667eea;
            background: #f8fafc;
        }
        
        .user-checkbox:checked + .user-checkbox-label {
            border-color: #667eea;
            background: #f0f4ff;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }
        
        .user-avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .user-name {
            font-weight: 500;
            color: #374151;
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
            <a href="/admin/tasks" class="nav-item">
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
                Create New Project
            </div>
            <div class="page-subtitle">Set up a new project for your team</div>
        </div>
        
        <!-- Form Container -->
        <div class="form-container">
            @if ($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                    <h4 style="margin-bottom: 8px; font-weight: 600;">Please fix the following errors:</h4>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if (session('error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                    {{ session('error') }}
                </div>
            @endif
            
            @if (session('success'))
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="/admin/custom-projects">
                @csrf
                
                <!-- Basic Information -->
                <div class="form-section">
                    <h3 class="section-title">Basic Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="name">Project Name *</label>
                            <input type="text" id="name" name="name" class="form-input" placeholder="Enter project name" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="planning" {{ old('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row form-row-full">
                        <div class="form-group">
                            <label class="form-label" for="description">Description</label>
                            <textarea id="description" name="description" class="form-textarea" placeholder="Describe your project goals and objectives">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Project Settings -->
                <div class="form-section">
                    <h3 class="section-title">Project Settings</h3>
                    
                    <div class="form-row form-row-full">
                        <div class="form-group">
                            <label class="form-label" for="assigned_users">Assigned Users</label>
                            <div class="user-checkbox-container">
                                @php
                                    $users = \App\Models\User::select(['id', 'name', 'profile_picture'])->get();
                                @endphp
                                @if($users->count() > 0)
                                    <div class="user-checkbox-grid">
                                        @foreach($users as $user)
                                            <div class="user-checkbox-item">
                                                <input type="checkbox" 
                                                       id="user-{{ $user->id }}" 
                                                       name="assigned_users[]" 
                                                       value="{{ $user->id }}"
                                                       class="user-checkbox">
                                                <label for="user-{{ $user->id }}" class="user-checkbox-label">
                                                    <div class="user-avatar-small">
                                                        <div style="width: 100%; height: 100%; border-radius: 50%; align-items: center; justify-content: center; background: #667eea; color: white; font-weight: 600; font-size: 12px; display: flex;">{{ substr($user->name, 0, 1) }}</div>
                                                    </div>
                                                    <span class="user-name">{{ $user->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div style="color: #6b7280; font-style: italic; padding: 20px; text-align: center;">
                                        No users available
                                    </div>
                                @endif
                            </div>
                            <small class="form-help">Select team members to assign to this project (optional)</small>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline -->
                <div class="form-section">
                    <h3 class="section-title">Timeline</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-date" value="{{ old('start_date') }}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="due_date">Due Date</label>
                            <input type="date" id="due_date" name="due_date" class="form-date" value="{{ old('due_date') }}">
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="/admin/custom-projects" class="btn btn-outline">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Project
                    </button>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        // Set default start date to today
        document.getElementById('start_date').valueAsDate = new Date();
        
        // Debug function to check form data before submission
        document.getElementById('create-project-form').addEventListener('submit', function(e) {
            const selectedCheckboxes = document.querySelectorAll('input[name="assigned_users[]"]:checked');
            const selectedUserIds = Array.from(selectedCheckboxes).map(cb => cb.value);
            console.log('Selected user IDs for project:', selectedUserIds);
        });
    </script>
</body>
</html>
