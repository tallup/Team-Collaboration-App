# Email Notifications Setup Guide

## Overview
Your USGamNeeds Team Collaboration app now has a comprehensive email notification system that sends emails for:

- ✅ **Task Assignment**: When a user is assigned to a new task
- ✅ **Task Status Changes**: When a task status is updated
- ✅ **Project Assignment**: When a user is assigned to a new project
- ✅ **Task Comments**: When a comment is added to a task

## Email Configuration

### Option 1: Log Driver (Default - For Testing)
The app is currently configured to use the `log` driver, which writes emails to the log file instead of sending them. This is perfect for development and testing.

**To view sent emails:**
```bash
tail -f storage/logs/laravel.log
```

### Option 2: SMTP Configuration (For Production)
To send actual emails, update your `.env` file with SMTP settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="USGamNeeds Team"
```

### Option 3: Mailtrap (For Testing)
For testing emails without sending real emails:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

## Testing Email Notifications

### Test Route
Visit `/test-email` in your browser to send a test notification to the first user in your database.

### Manual Testing
1. Create a new task and assign it to users
2. Change a task status
3. Add a comment to a task
4. Create a new project and assign users

## Email Templates

The system uses Laravel's built-in mail templates with custom styling. All emails include:

- Professional USGamNeeds branding
- Task/project details
- Action buttons to view details
- Responsive design

## Notification Types

### 1. Task Assigned
- **Triggered**: When a task is created and users are assigned
- **Recipients**: All assigned users (except the creator)
- **Content**: Task title, description, priority, due date, project info

### 2. Task Status Changed
- **Triggered**: When a task status is updated via the Kanban board
- **Recipients**: All assigned users (except the user who made the change)
- **Content**: Task details, old status, new status, who made the change

### 3. Project Assigned
- **Triggered**: When a project is created and users are assigned
- **Recipients**: All assigned users (except the creator)
- **Content**: Project name, description, status, dates

### 4. Task Comment Added
- **Triggered**: When a comment is added to a task
- **Recipients**: All assigned users (except the commenter)
- **Content**: Task details, comment content, commenter info

## Queue Configuration (Optional)

For better performance, emails are queued by default. To process the queue:

```bash
php artisan queue:work
```

## Customization

### Adding New Notification Types
1. Create a new notification class:
```bash
php artisan make:notification YourNotification
```

2. Implement the notification in your routes/controllers

### Customizing Email Templates
Edit the notification classes in `app/Notifications/` to customize email content and styling.

## Troubleshooting

### Emails Not Sending
1. Check your `.env` file configuration
2. Verify SMTP credentials
3. Check the log file for errors: `storage/logs/laravel.log`

### Testing in Development
- Use the `log` driver to see email content in logs
- Use Mailtrap for safe email testing
- Use the `/test-email` route for quick testing

## Security Notes

- Never commit real email credentials to version control
- Use app-specific passwords for Gmail
- Consider using environment-specific configurations
- Test thoroughly before going to production

## Next Steps

1. **Configure SMTP**: Set up real email sending for production
2. **Add User Preferences**: Allow users to opt-out of certain notifications
3. **Email Templates**: Customize the email design to match your brand
4. **Notification Center**: Add an in-app notification center
5. **Digest Emails**: Send daily/weekly summary emails
