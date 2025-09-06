# 🚀 Bluehost Deployment - Step by Step Guide

## Prerequisites
- Bluehost shared hosting account
- Domain name pointed to Bluehost
- FTP/SFTP access to your hosting account
- cPanel access

## Step 1: Prepare Your Local Project

### 1.1 Run the Deployment Script
```bash
# Make the script executable (Linux/Mac)
chmod +x bluehost-deploy.sh
./bluehost-deploy.sh

# Or run the PHP version (Windows)
php deploy-to-bluehost.php
```

### 1.2 Verify Files
- Check that `bluehost-deployment/` directory is created
- Verify all Laravel files are included
- Ensure `.htaccess` file is present

## Step 2: Bluehost cPanel Setup

### 2.1 Create MySQL Database
1. Log into your Bluehost cPanel
2. Navigate to **"MySQL Databases"**
3. Create a new database:
   - Database Name: `yourdomain_teamcollab`
   - Note down the full database name (usually includes your username prefix)
4. Create a database user:
   - Username: `yourdomain_dbuser`
   - Password: Generate a strong password
   - Note down the full username (includes prefix)
5. Add user to database with **ALL PRIVILEGES**

### 2.2 Create Email Account
1. Go to **"Email Accounts"** in cPanel
2. Create new email account:
   - Email: `noreply@yourdomain.com`
   - Password: Generate a strong password
   - Note down the credentials

### 2.3 Enable SSL (Important!)
1. Go to **"SSL/TLS"** in cPanel
2. Enable **"Force HTTPS Redirect"**
3. This ensures your app runs on HTTPS

## Step 3: Upload Files to Bluehost

### 3.1 Upload Method 1: File Manager (Recommended)
1. Go to **"File Manager"** in cPanel
2. Navigate to `public_html` directory
3. Upload the `team-collab-app-bluehost.tar.gz` file
4. Extract the archive in `public_html`
5. Move all files from the extracted folder to `public_html` root

### 3.2 Upload Method 2: FTP/SFTP
1. Use FileZilla or similar FTP client
2. Connect to your Bluehost server
3. Upload all files to `public_html` directory
4. Maintain the directory structure

### 3.3 Set File Permissions
1. In File Manager, select all files
2. Right-click → **"Permissions"**
3. Set permissions:
   - Directories: `755`
   - Files: `644`
   - `storage/` and `bootstrap/cache/`: `775`

## Step 4: Configure Environment

### 4.1 Create .env File
1. In File Manager, navigate to `public_html`
2. Rename `.env.production` to `.env`
3. Edit the `.env` file with your Bluehost settings:

```env
APP_NAME="USGamNeeds Team Collaboration"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=yourdomain_teamcollab
DB_USERNAME=yourdomain_dbuser
DB_PASSWORD=your_database_password

MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="USGamNeeds Team Collaboration"
```

## Step 5: Run Deployment Commands

### 5.1 Access Terminal
1. In cPanel, go to **"Terminal"** or **"SSH Access"**
2. Navigate to your domain directory:
   ```bash
   cd public_html
   ```

### 5.2 Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 5.3 Generate Application Key
```bash
php artisan key:generate
```

### 5.4 Run Database Migrations
```bash
php artisan migrate --force
```

### 5.5 Seed Sample Data
```bash
php artisan db:seed --class=ChatSeeder
```

### 5.6 Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 6: Test Your Application

### 6.1 Basic Functionality
1. Visit `https://yourdomain.com`
2. You should see the login page
3. Test login with your admin credentials

### 6.2 Test Features
- ✅ **Dashboard**: Check if stats load correctly
- ✅ **Projects**: Create and view projects
- ✅ **Tasks**: Create tasks and test Kanban board
- ✅ **Users**: View and manage users
- ✅ **Chat**: Test real-time chat functionality
- ✅ **Video Calls**: Test video conferencing
- ✅ **File Sharing**: Upload and share files
- ✅ **Email Notifications**: Test email sending

### 6.3 Test Email Notifications
1. Create a new task and assign it to a user
2. Check if email notifications are sent
3. Test the `/test-email` route if needed

## Step 7: Security & Optimization

### 7.1 Security Checklist
- ✅ `APP_DEBUG=false` in .env
- ✅ Strong database passwords
- ✅ SSL/HTTPS enabled
- ✅ Proper file permissions set
- ✅ Sensitive files protected

### 7.2 Performance Optimization
- ✅ Configuration cached
- ✅ Routes cached
- ✅ Views cached
- ✅ Composer optimized

## Step 8: Go Live!

### 8.1 Final Steps
1. Test all functionality thoroughly
2. Create your first admin user if needed
3. Set up your team members
4. Configure email settings
5. Test the complete workflow

### 8.2 Monitor Your Application
- Check error logs in `storage/logs/`
- Monitor database performance
- Test email delivery
- Verify file uploads work

## Troubleshooting

### Common Issues:

**1. 500 Internal Server Error**
- Check file permissions
- Verify .env configuration
- Check error logs

**2. Database Connection Error**
- Verify database credentials
- Check if database exists
- Ensure user has proper privileges

**3. Email Not Working**
- Verify SMTP settings
- Check email account credentials
- Test with Bluehost email settings

**4. File Upload Issues**
- Check storage directory permissions
- Verify file size limits
- Check PHP upload settings

**5. Chat Not Working**
- Verify database tables exist
- Check if migrations ran successfully
- Test API endpoints

## Support

If you encounter issues:
1. Check the error logs in `storage/logs/laravel.log`
2. Verify all steps were completed correctly
3. Test each feature individually
4. Contact Bluehost support for server-related issues

## 🎉 Congratulations!

Your team collaboration app is now live on Bluehost with:
- ✅ Real-time chat system
- ✅ Video conferencing
- ✅ File sharing with version control
- ✅ @Mentions and threaded discussions
- ✅ Email notifications
- ✅ Task and project management
- ✅ User management
- ✅ Modern, responsive design

**Your team can now start collaborating effectively!** 🚀
