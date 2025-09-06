#!/bin/bash

echo "=== BLUEHOST DEPLOYMENT SCRIPT ==="
echo "This script will help you deploy your Laravel app to Bluehost"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: This script must be run from the Laravel project root directory."
    exit 1
fi

echo "✅ Laravel project detected"
echo ""

# Create deployment package
echo "📦 Creating deployment package..."

# Create deployment directory
DEPLOY_DIR="bluehost-deployment"
mkdir -p $DEPLOY_DIR

# Copy essential files (excluding development files)
echo "📋 Copying files to deployment directory..."

# Copy all files except excluded ones
rsync -av --exclude='.git' \
         --exclude='node_modules' \
         --exclude='storage/logs' \
         --exclude='storage/framework/cache' \
         --exclude='storage/framework/sessions' \
         --exclude='storage/framework/views' \
         --exclude='tests' \
         --exclude='phpunit.xml' \
         --exclude='.env' \
         --exclude='deploy-to-bluehost.php' \
         --exclude='bluehost-deploy.sh' \
         --exclude='BLUEHOST_DEPLOYMENT.md' \
         --exclude='EMAIL_SETUP.md' \
         --exclude='README-UI-IMPROVEMENTS.md' \
         . $DEPLOY_DIR/

echo "✅ Files copied to $DEPLOY_DIR/"
echo ""

# Create production .env template
echo "📝 Creating production .env template..."
cat > $DEPLOY_DIR/.env.production << 'EOF'
APP_NAME="USGamNeeds Team Collaboration"
APP_ENV=production
APP_KEY=
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
EOF

echo "✅ Production .env template created"
echo ""

# Create deployment instructions
echo "📋 Creating deployment instructions..."
cat > $DEPLOY_DIR/DEPLOYMENT_INSTRUCTIONS.txt << 'EOF'
BLUEHOST DEPLOYMENT INSTRUCTIONS
================================

1. BLUEHOST SETUP:
   - Log into your Bluehost cPanel
   - Go to "MySQL Databases"
   - Create database: yourdomain_teamcollab
   - Create user with ALL PRIVILEGES
   - Go to "Email Accounts" and create: noreply@yourdomain.com

2. FILE UPLOAD:
   - Upload ALL files from this directory to your domain's root
   - The .htaccess file will redirect to public/ folder
   - Set permissions: 755 for directories, 644 for files

3. CONFIGURE .ENV:
   - Rename .env.production to .env
   - Update database credentials
   - Update email settings
   - Update APP_URL to your domain

4. RUN COMMANDS (via cPanel Terminal or SSH):
   composer install --optimize-autoloader --no-dev
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --class=ChatSeeder
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

5. TEST:
   - Visit your domain
   - Test login functionality
   - Test chat system
   - Test file uploads
   - Test email notifications

6. SECURITY:
   - Enable SSL/HTTPS in cPanel
   - Set APP_DEBUG=false
   - Use strong passwords
   - Check file permissions

Your team collaboration app is now live!
EOF

echo "✅ Deployment instructions created"
echo ""

# Create a compressed archive for easy upload
echo "📦 Creating compressed archive..."
tar -czf team-collab-app-bluehost.tar.gz -C $DEPLOY_DIR .
echo "✅ Archive created: team-collab-app-bluehost.tar.gz"
echo ""

echo "=== DEPLOYMENT PACKAGE READY ==="
echo "📁 Directory: $DEPLOY_DIR/"
echo "📦 Archive: team-collab-app-bluehost.tar.gz"
echo ""
echo "Next steps:"
echo "1. Upload files to Bluehost"
echo "2. Set up database and email"
echo "3. Configure .env file"
echo "4. Run deployment commands"
echo "5. Test your application"
echo ""
echo "🎉 Your team collaboration app is ready for Bluehost!"
