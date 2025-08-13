# Deployment Guide

## 🚀 Tổng quan

Hướng dẫn deploy Laravel Admin System lên production server một cách an toàn và hiệu quả. Bao gồm cấu hình server, optimization, monitoring và maintenance.

## 🏗️ Server Requirements

### Minimum Requirements
```
- PHP 8.1+ với extensions:
  - BCMath, Ctype, Fileinfo, JSON, Mbstring
  - OpenSSL, PDO, Tokenizer, XML, cURL
  - GD hoặc Imagick (cho image processing)
  - Redis extension (khuyến nghị PhpRedis)
- MySQL 8.0+ hoặc MariaDB 10.3+
- Redis 6.0+
- Nginx hoặc Apache
- SSL Certificate
- Composer 2.0+
- Node.js 16+ và NPM (cho asset building)
```

### Recommended Server Specs
```
- CPU: 2+ cores
- RAM: 4GB+ (8GB khuyến nghị)
- Storage: SSD 20GB+ (50GB khuyến nghị)
- Bandwidth: Không giới hạn
- OS: Ubuntu 20.04 LTS hoặc CentOS 8+
```

## ⚙️ Server Setup

### 1. PHP Configuration
```ini
# /etc/php/8.1/fpm/php.ini
memory_limit = 512M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20

# Extensions
extension=gd
extension=redis
extension=imagick

# OpCache (recommended)
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

### 2. Nginx Configuration
```nginx
# /etc/nginx/sites-available/laravel-admin
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/laravel-admin/public;

    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM Configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Static Assets Caching
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Security - Block access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    # File upload size
    client_max_body_size 10M;
}
```

### 3. Redis Configuration
```redis
# /etc/redis/redis.conf
bind 127.0.0.1
port 6379
timeout 0
tcp-keepalive 300

# Memory Management
maxmemory 256mb
maxmemory-policy allkeys-lru

# Persistence
save 900 1
save 300 10
save 60 10000

# Security
requirepass your_redis_password
```

### 4. MySQL Configuration
```sql
# /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
max_connections = 200
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
query_cache_type = 1
query_cache_size = 256M
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

## 📦 Deployment Process

### 1. Code Deployment
```bash
# Clone repository
git clone https://github.com/your-repo/laravel-admin.git /var/www/laravel-admin
cd /var/www/laravel-admin

# Checkout production branch
git checkout production

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci --production

# Build assets
npm run build

# Set permissions
sudo chown -R www-data:www-data /var/www/laravel-admin
sudo chmod -R 755 /var/www/laravel-admin
sudo chmod -R 775 /var/www/laravel-admin/storage
sudo chmod -R 775 /var/www/laravel-admin/bootstrap/cache
```

### 2. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database and Redis
vim .env
```

### 3. Production Environment
```bash
# .env production settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=production_db
DB_USERNAME=production_user
DB_PASSWORD=secure_password

# Redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=your_redis_password
REDIS_PORT=6379

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate --force

# Seed initial data (if needed)
php artisan db:seed --force

# Create admin user
php artisan admin:create
```

### 5. Optimization Commands
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Cache events
php artisan event:cache

# Optimize autoloader
composer dump-autoload --optimize
```

## 🔄 Zero-Downtime Deployment

### 1. Deployment Script
```bash
#!/bin/bash
# deploy.sh

APP_DIR="/var/www/laravel-admin"
RELEASE_DIR="/var/www/releases"
SHARED_DIR="/var/www/shared"
TIMESTAMP=$(date +%Y%m%d%H%M%S)
RELEASE_PATH="$RELEASE_DIR/$TIMESTAMP"

echo "🚀 Starting deployment..."

# Create release directory
mkdir -p $RELEASE_PATH

# Clone latest code
git clone --depth 1 https://github.com/your-repo/laravel-admin.git $RELEASE_PATH

# Install dependencies
cd $RELEASE_PATH
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build

# Link shared files
ln -nfs $SHARED_DIR/.env $RELEASE_PATH/.env
ln -nfs $SHARED_DIR/storage $RELEASE_PATH/storage

# Set permissions
chown -R www-data:www-data $RELEASE_PATH
chmod -R 755 $RELEASE_PATH

# Run migrations
php artisan migrate --force

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Switch to new release
ln -nfs $RELEASE_PATH $APP_DIR

# Restart services
sudo systemctl reload php8.1-fpm
sudo systemctl reload nginx

# Clean old releases (keep last 5)
cd $RELEASE_DIR && ls -t | tail -n +6 | xargs -r rm -rf

echo "✅ Deployment completed successfully!"
```

### 2. Automated Deployment với GitHub Actions
```yaml
# .github/workflows/deploy.yml
name: Deploy to Production

on:
  push:
    branches: [ production ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql, dom, filter, gd, iconv, json, mbstring, redis
        tools: composer:v2
        
    - name: Install dependencies
      run: composer install --no-dev --optimize-autoloader --no-interaction
      
    - name: Build assets
      run: |
        npm ci
        npm run build
        
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.DEPLOY_KEY }}
        script: |
          cd /var/www/laravel-admin
          git pull origin production
          composer install --no-dev --optimize-autoloader
          npm ci --production
          npm run build
          php artisan migrate --force
          php artisan config:cache
          php artisan route:cache
          php artisan view:cache
          sudo systemctl reload php8.1-fpm
```

## 📊 Monitoring & Logging

### 1. Application Monitoring
```php
// config/logging.php - Production logging
'channels' => [
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'error'),
        'days' => 30,
        'replace_placeholders' => true,
    ],
    
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'critical',
    ],
],
```

### 2. Server Monitoring Scripts
```bash
#!/bin/bash
# monitor.sh - Basic health check

# Check disk space
DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 85 ]; then
    echo "WARNING: Disk usage is ${DISK_USAGE}%"
fi

# Check memory usage
MEM_USAGE=$(free | grep Mem | awk '{printf("%.0f", $3/$2 * 100.0)}')
if [ $MEM_USAGE -gt 85 ]; then
    echo "WARNING: Memory usage is ${MEM_USAGE}%"
fi

# Check if services are running
systemctl is-active --quiet nginx || echo "ERROR: Nginx is not running"
systemctl is-active --quiet php8.1-fpm || echo "ERROR: PHP-FPM is not running"
systemctl is-active --quiet mysql || echo "ERROR: MySQL is not running"
systemctl is-active --quiet redis || echo "ERROR: Redis is not running"

# Check Laravel application
curl -s -o /dev/null -w "%{http_code}" http://localhost | grep -q "200" || echo "ERROR: Laravel app is not responding"
```

### 3. Log Rotation
```bash
# /etc/logrotate.d/laravel
/var/www/laravel-admin/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    copytruncate
}
```

## 🔒 Security Hardening

### 1. File Permissions
```bash
# Set proper ownership
chown -R www-data:www-data /var/www/laravel-admin

# Set directory permissions
find /var/www/laravel-admin -type d -exec chmod 755 {} \;

# Set file permissions
find /var/www/laravel-admin -type f -exec chmod 644 {} \;

# Writable directories
chmod -R 775 /var/www/laravel-admin/storage
chmod -R 775 /var/www/laravel-admin/bootstrap/cache
```

### 2. Environment Security
```bash
# Secure .env file
chmod 600 /var/www/laravel-admin/.env
chown www-data:www-data /var/www/laravel-admin/.env

# Remove sensitive files
rm -f /var/www/laravel-admin/.env.example
rm -f /var/www/laravel-admin/README.md
rm -rf /var/www/laravel-admin/.git
```

### 3. Firewall Configuration
```bash
# UFW Firewall rules
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 'Nginx Full'
ufw enable
```

## 🚨 Backup Strategy

### 1. Database Backup
```bash
#!/bin/bash
# backup-db.sh

DB_NAME="production_db"
DB_USER="production_user"
DB_PASS="secure_password"
BACKUP_DIR="/var/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Create database dump
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/db_backup_$DATE.sql

# Remove backups older than 7 days
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +7 -delete

echo "Database backup completed: db_backup_$DATE.sql.gz"
```

### 2. File Backup
```bash
#!/bin/bash
# backup-files.sh

APP_DIR="/var/www/laravel-admin"
BACKUP_DIR="/var/backups/files"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup important directories
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz \
    $APP_DIR/storage/app \
    $APP_DIR/public/images \
    $APP_DIR/.env

# Remove backups older than 30 days
find $BACKUP_DIR -name "files_backup_*.tar.gz" -mtime +30 -delete

echo "Files backup completed: files_backup_$DATE.tar.gz"
```

### 3. Automated Backup với Cron
```bash
# crontab -e
# Database backup daily at 2 AM
0 2 * * * /path/to/backup-db.sh

# File backup weekly on Sunday at 3 AM
0 3 * * 0 /path/to/backup-files.sh

# Health check every 5 minutes
*/5 * * * * /path/to/monitor.sh
```

## 🔄 Maintenance

### 1. Regular Maintenance Tasks
```bash
#!/bin/bash
# maintenance.sh

echo "🔧 Starting maintenance tasks..."

# Clear expired cache
php artisan cache:prune-stale-tags

# Clean old logs
find storage/logs -name "*.log" -mtime +30 -delete

# Optimize images
find public/images -name "*.jpg" -o -name "*.png" | xargs jpegoptim --strip-all

# Update sitemap
php artisan sitemap:generate

# Clear view cache
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Maintenance completed!"
```

### 2. Performance Monitoring
```bash
# Check Redis memory usage
redis-cli info memory

# Check MySQL slow queries
mysqladmin -u root -p processlist

# Check PHP-FPM status
curl http://localhost/php-fpm-status

# Check Nginx access logs for errors
tail -f /var/log/nginx/error.log
```

## 🚀 Scaling Considerations

### 1. Load Balancer Setup
```nginx
# /etc/nginx/conf.d/upstream.conf
upstream laravel_backend {
    server 192.168.1.10:80 weight=3;
    server 192.168.1.11:80 weight=2;
    server 192.168.1.12:80 weight=1;
}

server {
    listen 80;
    server_name yourdomain.com;
    
    location / {
        proxy_pass http://laravel_backend;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}
```

### 2. CDN Integration
```php
// config/filesystems.php
'disks' => [
    'cdn' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
        'endpoint' => env('AWS_ENDPOINT'),
    ],
],
```

### 3. Queue Workers
```bash
# Supervisor configuration
# /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laravel-admin/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=3
redirect_stderr=true
stdout_logfile=/var/www/laravel-admin/storage/logs/worker.log
```

## 📋 Deployment Checklist

### Pre-deployment
- [ ] Code review completed
- [ ] Tests passed
- [ ] Database migrations tested
- [ ] Environment variables configured
- [ ] SSL certificate installed
- [ ] Backup completed

### Deployment
- [ ] Code deployed
- [ ] Dependencies installed
- [ ] Assets built and optimized
- [ ] Database migrated
- [ ] Caches cleared and rebuilt
- [ ] Services restarted

### Post-deployment
- [ ] Application accessible
- [ ] SSL working correctly
- [ ] Database connection verified
- [ ] Cache working
- [ ] Email sending tested
- [ ] File uploads working
- [ ] Performance monitored
- [ ] Error logs checked
