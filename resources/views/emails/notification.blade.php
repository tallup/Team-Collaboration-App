<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e9ecef;
        }
        .task-details {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>USGamNeeds Team Collaboration</h1>
    </div>
    
    <div class="content">
        <h2>{{ $greeting ?? 'Hello!' }}</h2>
        
        <p>{{ $message ?? 'You have a new notification.' }}</p>
        
        @if(isset($details) && is_array($details))
            <div class="task-details">
                <h3>{{ $details['title'] ?? 'Details' }}</h3>
                @foreach($details as $key => $value)
                    @if($key !== 'title' && $value)
                        <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</p>
                    @endif
                @endforeach
            </div>
        @endif
        
        @if(isset($actionUrl))
            <a href="{{ $actionUrl }}" class="button">View Details</a>
        @endif
        
        <div class="footer">
            <p>Thank you for using USGamNeeds Team Collaboration!</p>
            <p>This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
