# Bluehost Shared Hosting Deployment Guide

## Prerequisites
- Bluehost shared hosting account
- Domain name pointed to Bluehost
- FTP/SFTP access to your hosting account
- Database access (MySQL)

## Step 1: Prepare Your Laravel Project

### 1.1 Optimize for Production
```bash
# Install dependencies for production
composer install --optimize-autoloader --no-dev

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate application key (if not set)
php artisan key:generate
```

### 1.2 Create Production .env File
Create a `.env.production` file with your Bluehost settings:

```env
APP_NAME="USGamNeeds Team Collaboration"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_bluehost_db_name
DB_USERNAME=your_bluehost_db_user
DB_PASSWORD=your_bluehost_db_password

MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Step 2: Bluehost Setup

### 2.1 Database Setup
1. Log into Bluehost cPanel
2. Go to "MySQL Databases"
3. Create a new database (e.g., `yourdomain_teamcollab`)
4. Create a database user
5. Add user to database with ALL PRIVILEGES
6. Note down the database details

### 2.2 File Structure on Bluehost
Your files should be uploaded to the `public_html` directory:

```
public_html/
├── index.php (Laravel's public/index.php)
├── .htaccess (Laravel's public/.htaccess)
├── assets/ (CSS, JS, images)
├── storage/ (symlinked to ../storage/app/public)
└── [other Laravel files in parent directory]
```

## Step 3: Upload Files

### 3.1 Upload Method 1: Direct Upload
1. Compress your entire Laravel project
2. Upload to Bluehost via File Manager or FTP
3. Extract in the root directory (not public_html)

### 3.2 Upload Method 2: Git (Recommended)
If Bluehost supports Git:
```bash
# On Bluehost, clone your repository
git clone https://github.com/yourusername/team-collab-app.git
cd team-collab-app
composer install --optimize-autoloader --no-dev
```

## Step 4: Configure Web Server

### 4.1 Create .htaccess in Root Directory
Create `.htaccess` in your project root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 4.2 Update public/index.php
The public/index.php should point to the correct paths:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
```

## Step 5: Set Permissions

Set proper file permissions:
```bash
# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Set storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## Step 6: Run Migrations

After uploading, run:
```bash
php artisan migrate --force
php artisan db:seed (if you have seeders)
```

## Step 7: Configure Email

### 7.1 Bluehost Email Setup
1. Create an email account in Bluehost cPanel (e.g., noreply@yourdomain.com)
2. Use these SMTP settings in your .env:
   - Host: mail.yourdomain.com
   - Port: 587
   - Username: noreply@yourdomain.com
   - Password: [email password]

### 7.2 Test Email Configuration
Visit `/test-email` to test if emails are working.

## Step 8: SSL Certificate

1. In Bluehost cPanel, go to "SSL/TLS"
2. Enable "Force HTTPS Redirect"
3. Update your APP_URL to use https://

## Troubleshooting

### Common Issues:

1. **500 Error**: Check file permissions and .htaccess
2. **Database Connection**: Verify database credentials
3. **File Not Found**: Ensure proper directory structure
4. **Email Not Working**: Check SMTP settings and email account

### Debug Mode:
Temporarily set `APP_DEBUG=true` in .env to see detailed errors.

## Security Checklist

- [ ] Set APP_DEBUG=false
- [ ] Use strong database passwords
- [ ] Enable SSL/HTTPS
- [ ] Set proper file permissions
- [ ] Remove unnecessary files
- [ ] Configure proper .htaccess rules

## Performance Optimization

1. Enable OPcache in PHP settings
2. Use CDN for static assets
3. Optimize images
4. Enable Gzip compression
5. Set up caching

## Backup Strategy

1. Regular database backups
2. File system backups
3. Automated backups via cPanel
4. Version control with Git

## Support

If you encounter issues:
1. Check Bluehost error logs
2. Check Laravel logs in storage/logs/
3. Contact Bluehost support for server-related issues
4. Check Laravel documentation for application issues
