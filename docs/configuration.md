# Configuration

## 🔧 Cấu hình hệ thống

### Environment Setup

Cấu hình file `.env` cho hệ thống:

```bash
# Application
APP_NAME=Laravel
APP_ENV=production  # local/production
APP_KEY=base64:...
APP_DEBUG=false    # true for development
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=

# Redis Configuration
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
QUEUE_CONNECTION=redis

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🗄️ Redis Configuration

### Predis Setup
```php
// config/database.php
'redis' => [
    'client' => env('REDIS_CLIENT', 'predis'),
    'options' => [
        'cluster' => env('REDIS_CLUSTER', 'redis'),
        'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
    ],
    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'username' => env('REDIS_USERNAME'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
    ],
    'cache' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'username' => env('REDIS_USERNAME'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_CACHE_DB', '1'),
    ],
],
```

### Cache TTL Configuration
```php
// config/cache.php - Custom TTL settings
'ttl' => [
    'slider_cache' => 3600,    // 1 hour
    'news_cache' => 3600,      // 1 hour  
    'feedback_cache' => 3600,  // 1 hour
    'product_cache' => 7200,   // 2 hours
    'category_cache' => 14400, // 4 hours
],
```

## 🖼️ Image Configuration

### Image Processing Settings
```php
// Default image config in ImageHandlerTrait
protected $defaultImageConfig = [
    'convertToWebp' => true,
    'quality' => 80,
    'mimeTypes' => [
        'image/jpeg', 
        'image/png',
        'image/jpg', 
        'image/gif',
        'image/webp'
    ]
];
```

### Storage Configuration
```php
// config/filesystems.php
'default' => env('FILESYSTEM_DISK', 'local'),

'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
    ],
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

## 📧 Queue Configuration

### Redis Queue Setup
```php
// config/queue.php
'default' => env('QUEUE_CONNECTION', 'redis'),

'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```

## 🔒 Security Configuration

### CORS Settings
```php
// config/cors.php
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => false,
```

### Session Security
```php
// config/session.php
'lifetime' => env('SESSION_LIFETIME', 120),
'expire_on_close' => false,
'encrypt' => false,
'files' => storage_path('framework/sessions'),
'connection' => null,
'table' => 'sessions',
'store' => null,
'lottery' => [2, 100],
'cookie' => env('SESSION_COOKIE', Str::slug(env('APP_NAME', 'laravel'), '_').'_session'),
'path' => '/',
'domain' => env('SESSION_DOMAIN', null),
'secure' => env('SESSION_SECURE_COOKIE'),
'http_only' => true,
'same_site' => 'lax',
```

## 🚀 Performance Configuration

### Optimization Settings
```bash
# Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Database Optimization
```php
// config/database.php MySQL settings
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
],
```

## 🛠️ Development Configuration

### Debug Configuration
```bash
# Development environment
APP_ENV=local
APP_DEBUG=true
DEBUGBAR_ENABLED=true

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

### Asset Configuration
```bash
# Asset building
npm install
npm run dev     # Development
npm run build   # Production
npm run watch   # Watch mode
```

## 📝 Custom Configuration

### Helper Files Autoloading
```php
// composer.json
"autoload-dev": {
    "files": [
        "app/Helpers/CateHelper.php",
        "app/Helpers/MenuHelper.php",
        "app/Helpers/CartHelper.php"
    ]
}
```

### Service Providers
```php
// config/app.php
'providers' => [
    // Laravel Framework Service Providers...
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\BroadcastServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
],
```
