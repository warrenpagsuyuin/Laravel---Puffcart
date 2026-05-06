# 🚀 PUFFCART SECURITY - DEPLOYMENT GUIDE

## PRE-DEPLOYMENT CHECKLIST

---

## Phase 1: LOCAL DEVELOPMENT (✅ Already Done)

### ✅ Complete
- [x] All 20 security requirements implemented
- [x] 26 new files created
- [x] 6 existing files updated
- [x] Migrations created
- [x] Models and services built
- [x] Controllers enhanced
- [x] Views created
- [x] Documentation completed

### Verify Local Setup
```bash
# Run migrations
php artisan migrate

# Seed admin account
php artisan db:seed --class=AdminSeeder

# Start development server
php artisan serve

# Test login at http://localhost:8000/admin/login
# Use: admin@puffcart.local / admin123
```

---

## Phase 2: PRODUCTION PREPARATION

### Step 1: Change Admin Password ⚠️ CRITICAL
```bash
php artisan tinker

# Generate new password hash
use App\Models\User;
$user = User::where('email', 'admin@puffcart.local')->first();
$user->password = Hash::make('YOUR_SECURE_PASSWORD_HERE');
$user->save();
exit;
```

**Choose a secure password:**
- At least 16 characters
- Mix of uppercase, lowercase, numbers, symbols
- NO dictionary words
- Example: `P@ffC4rt!Secure2024#Admin`

### Step 2: Update Environment Variables

Create production `.env` file:

```bash
# Application
APP_NAME="Puffcart"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx (run php artisan key:generate)
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=puffcart
DB_USERNAME=root
DB_PASSWORD=xxxxxxxxxxxx

# Mail (required for MFA)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com (or your SMTP provider)
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Puffcart"

# PayMongo (CRITICAL - get from PayMongo dashboard)
PAYMONGO_PUBLIC_KEY=pk_live_xxxxxxxxxxxxx
PAYMONGO_SECRET_KEY=sk_live_xxxxxxxxxxxxx
PAYMONGO_WEBHOOK_SECRET=whsec_live_xxxxxxxxxxxxx

# reCAPTCHA v3 (CRITICAL - get from Google)
RECAPTCHA_SITE_KEY=xxxxxxxxxxxxx
RECAPTCHA_SECRET_KEY=xxxxxxxxxxxxx

# Session Security (CRITICAL for HTTPS)
SESSION_DRIVER=cookie
SESSION_SECURE_COOKIES=true
SESSION_SAME_SITE=lax

# Cache
CACHE_DRIVER=file

# Queue
QUEUE_CONNECTION=sync

# Redis (optional, for better performance)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Step 3: Get Required Keys

#### PayMongo Live Keys
1. Go to https://paymongo.com/dashboard
2. Navigate to Settings > API Keys
3. Copy LIVE public, secret, and webhook secret keys
4. Add to `.env` file

#### reCAPTCHA v3 Keys
1. Go to https://www.google.com/recaptcha/admin/create
2. Create new site for reCAPTCHA v3
3. Copy Site Key and Secret Key
4. Add to `.env` file

#### Email/SMTP Configuration
1. Set up SMTP service:
   - Gmail: Use app-specific password
   - SendGrid: Use API key
   - Mailgun: Use domain and API key
   - Or your own SMTP server
2. Configure MAIL_* variables in `.env`

### Step 4: Enable HTTPS

```bash
# Install SSL certificate (Let's Encrypt recommended)
sudo certbot certonly --standalone -d yourdomain.com

# Configure web server to use HTTPS
# Update web server config (nginx/Apache)
# Redirect HTTP to HTTPS
```

### Step 5: Enable MFA for Admins

```bash
php artisan tinker

use App\Models\User;
User::where('role', 'admin')->update(['mfa_enabled' => true]);
exit;
```

---

## Phase 3: DEPLOYMENT

### Step 1: Database Setup

```bash
# On production server
mysql -u root -p
CREATE DATABASE puffcart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'puffcart'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON puffcart.* TO 'puffcart'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 2: Application Deployment

```bash
# Clone repository
git clone https://github.com/yourepo/puffcart.git
cd puffcart

# Install dependencies
composer install --optimize-autoloader --no-dev

# Copy environment file
cp .env.example .env
# Edit .env with production values (from Phase 2)

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed admin account (optional, or do manually)
php artisan db:seed --class=AdminSeeder

# Build assets (if needed)
npm run build

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear

# Start queue worker (if using queued jobs)
php artisan queue:work &
```

### Step 3: Web Server Configuration

#### Nginx Configuration
```nginx
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    
    root /var/www/puffcart/public;
    index index.php index.html index.htm;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    # Redirect HTTP to HTTPS
    if ($scheme != "https") {
        return 301 https://$server_name$request_uri;
    }
}
```

#### Apache Configuration
```apache
<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/puffcart/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/yourdomain.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/yourdomain.com/privkey.pem

    # Security headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"

    <Directory /var/www/puffcart/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # PHP
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/var/run/php/php8.3-fpm.sock|fcgi://localhost"
    </FilesMatch>

    ErrorLog ${APACHE_LOG_DIR}/puffcart_error.log
    CustomLog ${APACHE_LOG_DIR}/puffcart_access.log combined
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName yourdomain.com
    Redirect permanent / https://yourdomain.com/
</VirtualHost>
```

---

## Phase 4: POST-DEPLOYMENT

### Step 1: Verify Installation

```bash
# Test application is running
curl https://yourdomain.com

# Test admin login
# Go to https://yourdomain.com/admin/login
# Use new admin credentials

# Check audit logs
# Go to https://yourdomain.com/admin/audit-logs
```

### Step 2: Test Security Features

#### Account Lockout
1. Go to `/admin/login`
2. Enter wrong password 3 times
3. Verify account is locked

#### MFA
1. Login with correct credentials
2. Should redirect to MFA page
3. Check email for 6-digit code (should be in logs)
4. Enter code to complete login

#### Payment System
1. Create order in cart
2. Proceed to checkout
3. Test with PayMongo test card
4. Verify payment status updates

#### Audit Logging
1. Perform admin actions
2. View `/admin/audit-logs`
3. Verify actions are logged

### Step 3: Configure Monitoring

```bash
# Install Sentry (error tracking)
php artisan make:provider SentryServiceProvider

# Configure email notifications
php artisan config:cache

# Set up log rotation
# In /etc/logrotate.d/puffcart
/var/www/puffcart/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### Step 4: Backup Configuration

```bash
# Automated daily backups
# Create backup script: /usr/local/bin/backup-puffcart.sh

#!/bin/bash
BACKUP_DIR="/backups/puffcart"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup database
mysqldump -u puffcart -p puffcart > $BACKUP_DIR/db_$DATE.sql

# Backup application files
tar -czf $BACKUP_DIR/app_$DATE.tar.gz /var/www/puffcart

# Delete old backups (keep 30 days)
find $BACKUP_DIR -mtime +30 -delete

# Cron job: 0 2 * * * /usr/local/bin/backup-puffcart.sh
```

---

## Phase 5: ONGOING MAINTENANCE

### Daily Tasks
- [ ] Monitor error logs: `tail -f storage/logs/laravel.log`
- [ ] Check audit logs for suspicious activity
- [ ] Monitor server health (CPU, memory, disk)
- [ ] Check backup completion status

### Weekly Tasks
- [ ] Review audit logs for patterns
- [ ] Test backup recovery process
- [ ] Monitor payment transactions
- [ ] Check for failed jobs (if using queue)

### Monthly Tasks
- [ ] Update security patches
- [ ] Review and optimize database performance
- [ ] Update SSL certificate if needed (auto with Let's Encrypt)
- [ ] Performance analysis and optimization

### Quarterly Tasks
- [ ] Security audit
- [ ] Penetration testing
- [ ] Code review
- [ ] Dependency updates
- [ ] Disaster recovery drill

---

## TROUBLESHOOTING

### Issue: 500 Error on Login
```bash
# Check logs
tail -f storage/logs/laravel.log

# Verify database connection
php artisan tinker
DB::connection()->getPdo();

# Verify migrations ran
php artisan migrate:status
```

### Issue: Email Not Sending for MFA
```bash
# Test SMTP configuration
php artisan tinker
Mail::raw('Test', function($message) {
    $message->to('test@example.com');
});

# Check mail logs
tail -f storage/logs/laravel.log
```

### Issue: PayMongo Webhook Not Working
```bash
# Verify webhook secret is correct
# Check .env PAYMONGO_WEBHOOK_SECRET

# Test webhook manually
php artisan tinker
# Manually trigger webhook handling
```

### Issue: High Memory Usage
```bash
# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Monitor processes
ps aux | grep php
top
```

---

## SECURITY HARDENING (OPTIONAL)

### Web Application Firewall
```bash
# Install ModSecurity with OWASP CRS
# Configure rules for Laravel
# Block SQL injection attempts
# Block XSS attempts
```

### Rate Limiting
```bash
# In routes/web.php
Route::middleware('throttle:60,1')->group(function () {
    // API endpoints
});

Route::middleware('throttle:20,1')->group(function () {
    // Login endpoints
});
```

### IP Whitelisting (Admin Panel)
```nginx
location /admin {
    allow 192.168.1.0/24;
    allow 203.0.113.50;
    deny all;
}
```

### Additional Headers
```nginx
# Content Security Policy
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline';" always;

# HSTS
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
```

---

## SUCCESS CHECKLIST

- [ ] Admin password changed to secure value
- [ ] HTTPS enabled on all routes
- [ ] .env configured with production values
- [ ] Database migrated successfully
- [ ] All 20 security features working
- [ ] PayMongo keys configured
- [ ] Email/SMTP configured
- [ ] MFA enabled for admins
- [ ] Backups configured and tested
- [ ] Monitoring/alerts set up
- [ ] Security audit completed
- [ ] Load testing performed
- [ ] Documentation updated
- [ ] Team trained on new features

---

## 📞 SUPPORT

### During Deployment
- Check Laravel logs: `storage/logs/laravel.log`
- Review documentation: `SECURITY_IMPLEMENTATION.md`
- Test each feature individually
- Contact hosting provider for server issues

### After Deployment
- Monitor audit logs daily
- Review error logs weekly
- Perform security updates monthly
- Conduct quarterly security audits

---

**Deployment Status:** Ready to deploy
**Security Level:** Enterprise-grade
**Estimated Downtime:** 15-30 minutes
**Rollback Plan:** Keep previous .env and database backup
