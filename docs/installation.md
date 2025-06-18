# Cài đặt

## 📋 Yêu cầu hệ thống

### Server Requirements
- **PHP**: 8.3 hoặc cao hơn
- **Composer**: 2.0+
- **Node.js**: 18.0+
- **NPM**: 8.0+

### PHP Extensions
```bash
php -m | grep -E "(openssl|pdo|mbstring|tokenizer|xml|ctype|json|bcmath|curl|fileinfo|gd)"
```

Required extensions:
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- BCMath PHP Extension
- cURL PHP Extension
- Fileinfo PHP Extension
- GD PHP Extension

### Database
- **MySQL**: 8.0+ (recommended)
- **PostgreSQL**: 13.0+
- **SQLite**: 3.8.8+

## 🚀 Cài đặt nhanh

### 1. Clone Repository

```bash
# Clone từ GitHub
git clone https://github.com/Thanhphuc1230/source_laravel.git
cd source_laravel

# Hoặc download ZIP và extract
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies  
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Chỉnh sửa file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_admin
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Database Migration

```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

### 6. Storage Setup

```bash
# Create symbolic link for storage
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 7. Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### 8. Start Development Server

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 🔧 Cài đặt chi tiết

### Environment Variables

File `.env` quan trọng:

```env
# Application
APP_NAME="Laravel Admin System"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_admin
DB_USERNAME=root
DB_PASSWORD=

# Cache
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Image Processing
IMAGE_QUALITY=80
IMAGE_CONVERT_WEBP=true
MAX_IMAGE_SIZE=2048
```

### Web Server Configuration

#### Apache (.htaccess)

File `public/.htaccess` đã có sẵn:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/laravel-admin-system/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🗄️ Database Setup

### Create Database

```sql
-- MySQL
CREATE DATABASE laravel_admin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- PostgreSQL
CREATE DATABASE laravel_admin WITH ENCODING 'UTF8';
```

### Migration Commands

```bash
# Check migration status
php artisan migrate:status

# Run specific migration
php artisan migrate --path=/database/migrations/2024_01_01_000000_create_products_table.php

# Rollback last migration
php artisan migrate:rollback

# Reset all migrations
php artisan migrate:reset

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Seeder Commands

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=ProductSeeder

# Create new seeder
php artisan make:seeder CategorySeeder
```

## 👤 Admin Account

Sau khi seed database, account admin mặc định:

```
Email: admin@example.com
Password: password
```

**⚠️ Quan trọng**: Đổi password ngay sau khi đăng nhập!

## 🔍 Troubleshooting

### Common Issues

#### 1. Permission Denied

```bash
# Linux/Mac
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 2. Composer Memory Limit

```bash
# Increase memory limit
php -d memory_limit=-1 /usr/local/bin/composer install
```

#### 3. Node.js Version

```bash
# Check version
node --version
npm --version

# Update Node.js using nvm
nvm install 18
nvm use 18
```

#### 4. Database Connection

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Log Files

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Web server logs
tail -f /var/log/nginx/error.log
tail -f /var/log/apache2/error.log
```

## ✅ Verification

Kiểm tra cài đặt thành công:

1. **Homepage**: `http://localhost:8000` - Hiển thị trang chủ
2. **Admin**: `http://localhost:8000/admin` - Hiển thị login admin
3. **Images**: Upload một hình ảnh để test image processing
4. **Database**: Kiểm tra tables đã được tạo

## 🚀 Next Steps

- [Cấu hình hệ thống](configuration.md)
- [Hướng dẫn sử dụng](content-management.md)
- [Tùy chỉnh giao diện](customization.md) 