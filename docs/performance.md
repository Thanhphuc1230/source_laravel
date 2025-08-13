# Performance Optimization

## ⚡ Tổng quan

Hướng dẫn tối ưu hóa hiệu suất cho Laravel Admin System. Bao gồm cache strategies, database optimization, image processing, và các kỹ thuật tăng tốc website.

## 🎯 Performance Metrics

### Target Performance
```
- Page Load Time: < 2 seconds
- Time to First Byte (TTFB): < 500ms
- First Contentful Paint (FCP): < 1.5 seconds
- Largest Contentful Paint (LCP): < 2.5 seconds
- Cumulative Layout Shift (CLS): < 0.1
- Admin Panel Response: < 1 second
```

### Measurement Tools
```bash
# PageSpeed Insights
curl "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=YOUR_URL"

# GTmetrix API
curl -u api_key: "https://gtmetrix.com/api/0.1/test"

# WebPageTest
curl "http://www.webpagetest.org/runtest.php?url=YOUR_URL&f=json"
```

## 🗄️ Database Optimization

### 1. Query Optimization
```php
// ❌ N+1 Query Problem
foreach (Product::all() as $product) {
    echo $product->category->name; // Triggers additional query
}

// ✅ Eager Loading
$products = Product::with('category')->get();
foreach ($products as $product) {
    echo $product->category->name; // No additional queries
}

// ✅ Select Only Needed Fields
$products = Product::select('id', 'name', 'image', 'category_id')
                  ->with('category:id,name')
                  ->get();
```

### 2. Database Indexing
```sql
-- Essential indexes for Laravel Admin System
CREATE INDEX idx_products_status ON tp_products(status);
CREATE INDEX idx_products_category ON tp_products(category_id);
CREATE INDEX idx_products_home ON tp_products(home);
CREATE INDEX idx_products_stt ON tp_products(stt);
CREATE INDEX idx_products_created ON tp_products(created_at);

-- Composite indexes for common queries
CREATE INDEX idx_products_status_stt ON tp_products(status, stt);
CREATE INDEX idx_products_category_status ON tp_products(category_id, status);

-- Full-text search indexes
CREATE FULLTEXT INDEX idx_products_search ON tp_products(name_vn, content_vn);
CREATE FULLTEXT INDEX idx_news_search ON tp_news(name_vn, content_vn, keywords);
```

### 3. Query Analysis
```sql
-- Analyze slow queries
SHOW PROCESSLIST;
SHOW FULL PROCESSLIST;

-- Check query execution plan
EXPLAIN SELECT * FROM tp_products 
WHERE category_id = 1 AND status = 1 
ORDER BY stt ASC;

-- Monitor slow query log
SHOW VARIABLES LIKE 'slow_query_log%';
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;
```

### 4. Database Configuration
```ini
# /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
# Buffer Pool (75% of available RAM)
innodb_buffer_pool_size = 6G
innodb_buffer_pool_instances = 6

# Log Files
innodb_log_file_size = 512M
innodb_log_buffer_size = 64M
innodb_flush_log_at_trx_commit = 2

# Query Cache
query_cache_type = 1
query_cache_size = 512M
query_cache_limit = 16M

# Connection Settings
max_connections = 300
max_user_connections = 250
connect_timeout = 10
wait_timeout = 300

# Temporary Tables
tmp_table_size = 256M
max_heap_table_size = 256M
```

## 🔄 Caching Strategies

### 1. Redis Configuration Optimization
```redis
# /etc/redis/redis.conf
# Memory optimization
maxmemory 2gb
maxmemory-policy allkeys-lru
maxmemory-samples 10

# Network optimization
tcp-keepalive 300
timeout 0

# Persistence optimization (for cache-only)
save ""
appendonly no

# Performance tuning
hz 100
client-output-buffer-limit normal 0 0 0
```

### 2. Application-Level Caching
```php
// Cache configuration optimization
// config/cache.php
'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
        'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),
        'serializer' => 'php', // Use 'igbinary' if available for better performance
    ],
],

// TTL configuration
'ttl' => [
    'slider_cache' => 3600,      // 1 hour
    'news_cache' => 3600,        // 1 hour
    'feedback_cache' => 3600,    // 1 hour
    'product_cache' => 7200,     // 2 hours
    'category_cache' => 14400,   // 4 hours
    'menu_cache' => 86400,       // 24 hours
    'page_cache' => 43200,       // 12 hours
],
```

### 3. Smart Caching Implementation
```php
// Cache with tags for easy invalidation
Cache::tags(['products', 'category:'.$categoryId])
     ->remember('products_by_category_'.$categoryId, 3600, function() use ($categoryId) {
         return Product::where('category_id', $categoryId)
                      ->where('status', 1)
                      ->with('category')
                      ->get();
     });

// Invalidate related caches
Cache::tags(['products', 'category:'.$categoryId])->flush();

// Cache warming strategy
class CacheWarmupCommand extends Command
{
    public function handle()
    {
        // Pre-populate frequently accessed data
        $this->warmProductCache();
        $this->warmCategoryCache();
        $this->warmMenuCache();
    }
    
    private function warmProductCache()
    {
        $categories = CateProduct::where('status', 1)->get();
        foreach ($categories as $category) {
            Cache::remember('products_category_'.$category->id, 7200, function() use ($category) {
                return Product::where('category_id', $category->id)
                             ->where('status', 1)
                             ->get();
            });
        }
    }
}
```

### 4. HTTP Caching
```php
// Controller-level caching
class ProductController extends Controller
{
    public function show($slug)
    {
        $cacheKey = 'product_'.$slug;
        
        return Cache::remember($cacheKey, 3600, function() use ($slug) {
            $product = Product::where('slug', $slug)->firstOrFail();
            return view('product.show', compact('product'));
        });
    }
}

// Response caching with ETags
class CacheMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        if ($request->isMethod('GET')) {
            $etag = md5($response->getContent());
            $response->setEtag($etag);
            
            if ($request->getETags() && in_array($etag, $request->getETags())) {
                return response('', 304);
            }
        }
        
        return $response;
    }
}
```

## 🖼️ Image Optimization

### 1. Advanced Image Processing
```php
// Enhanced ImageService with multiple formats
class ImageService
{
    public function processImage($file, $config = [])
    {
        $config = array_merge([
            'convertToWebp' => true,
            'generateResponsive' => true,
            'quality' => 80,
            'webpQuality' => 85,
            'sizes' => [
                'thumb' => [150, 150],
                'small' => [300, 300],
                'medium' => [600, 600],
                'large' => [1200, 1200],
            ]
        ], $config);
        
        $results = [];
        
        foreach ($config['sizes'] as $size => $dimensions) {
            // Original format
            $originalPath = $this->resizeImage($file, $dimensions, $config['quality']);
            $results[$size] = $originalPath;
            
            // WebP format
            if ($config['convertToWebp']) {
                $webpPath = $this->convertToWebP($originalPath, $config['webpQuality']);
                $results[$size.'_webp'] = $webpPath;
            }
        }
        
        return $results;
    }
    
    private function convertToWebP($imagePath, $quality = 85)
    {
        $webpPath = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $imagePath);
        
        $image = imagecreatefromstring(file_get_contents($imagePath));
        imagewebp($image, $webpPath, $quality);
        imagedestroy($image);
        
        return $webpPath;
    }
}
```

### 2. Responsive Images in Frontend
```php
// Helper for responsive images
function responsiveImage($imagePath, $alt = '', $class = '')
{
    $baseUrl = asset('images/');
    $pathInfo = pathinfo($imagePath);
    $filename = $pathInfo['filename'];
    $extension = $pathInfo['extension'];
    
    return sprintf(
        '<picture class="%s">
            <source srcset="%s" type="image/webp" media="(min-width: 768px)">
            <source srcset="%s" type="image/webp" media="(max-width: 767px)">
            <source srcset="%s" type="image/%s" media="(min-width: 768px)">
            <img src="%s" alt="%s" loading="lazy">
        </picture>',
        $class,
        $baseUrl . $filename . '_large.webp',
        $baseUrl . $filename . '_small.webp',
        $baseUrl . $filename . '_large.' . $extension,
        $extension,
        $baseUrl . $imagePath,
        $alt
    );
}
```

### 3. CDN Integration
```php
// config/filesystems.php
'disks' => [
    'cdn' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('CDN_URL'), // CloudFront URL
        'endpoint' => env('AWS_ENDPOINT'),
        'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    ],
],

// Image URL helper
function imageUrl($path, $size = 'medium')
{
    if (config('app.env') === 'production') {
        return config('filesystems.disks.cdn.url') . '/' . $path;
    }
    
    return asset('images/' . $path);
}
```

## 🚀 Frontend Performance

### 1. Asset Optimization
```javascript
// webpack.mix.js
const mix = require('laravel-mix');

mix.js('resources/js/admin.js', 'public/admin/js')
   .sass('resources/sass/admin.scss', 'public/admin/css')
   .options({
       processCssUrls: false,
       postCss: [
           require('autoprefixer'),
           require('cssnano')({
               preset: ['default', {
                   discardComments: { removeAll: true },
                   normalizeWhitespace: false,
               }]
           })
       ]
   })
   .sourceMaps(false, 'eval-cheap-module-source-map')
   .version();

// Enable minification in production
if (mix.inProduction()) {
    mix.minify(['public/admin/js/admin.js'])
       .minify(['public/admin/css/admin.css']);
}
```

### 2. Critical CSS
```php
// Generate critical CSS for above-the-fold content
class CriticalCSSService
{
    public function generate($url, $width = 1200, $height = 900)
    {
        $command = sprintf(
            'critical "%s" --base public/ --css public/admin/css/admin.css --target public/admin/css/critical.css --width %d --height %d --minify',
            $url,
            $width,
            $height
        );
        
        exec($command);
    }
}

// Include critical CSS inline
@push('critical-css')
<style>
{!! file_get_contents(public_path('admin/css/critical.css')) !!}
</style>
@endpush
```

### 3. JavaScript Optimization
```javascript
// Lazy loading implementation
const lazyImages = document.querySelectorAll('img[data-src]');
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            img.src = img.dataset.src;
            img.classList.remove('lazy');
            imageObserver.unobserve(img);
        }
    });
});

lazyImages.forEach(img => imageObserver.observe(img));

// Debounced search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

const searchInput = document.getElementById('search');
const debouncedSearch = debounce((query) => {
    fetch(`/admin/search?q=${query}`)
        .then(response => response.json())
        .then(data => updateResults(data));
}, 300);

searchInput.addEventListener('input', (e) => {
    debouncedSearch(e.target.value);
});
```

## 🔧 PHP Performance

### 1. OPcache Configuration
```ini
# /etc/php/8.1/mods-available/opcache.ini
[opcache]
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=512
opcache.interned_strings_buffer=64
opcache.max_accelerated_files=32531
opcache.validate_timestamps=0
opcache.save_comments=0
opcache.fast_shutdown=1
opcache.file_cache=/tmp/opcache
opcache.file_cache_only=0
opcache.file_cache_consistency_checks=1
opcache.huge_code_pages=1
```

### 2. PHP-FPM Optimization
```ini
# /etc/php/8.1/fpm/pool.d/www.conf
[www]
user = www-data
group = www-data
listen = /run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data

pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 15
pm.max_requests = 1000

# Process management
pm.process_idle_timeout = 10s
pm.status_path = /php-fpm-status
ping.path = /php-fpm-ping

# Slow request logging
request_slowlog_timeout = 5s
slowlog = /var/log/php-fpm-slow.log

# Security
security.limit_extensions = .php .php3 .php4 .php5 .php7 .php8
```

### 3. Memory Optimization
```php
// Memory-efficient data processing
class DataProcessor
{
    public function processLargeDataset()
    {
        // Use generators for large datasets
        foreach ($this->getProductsGenerator() as $product) {
            $this->processProduct($product);
            // Memory is freed after each iteration
        }
    }
    
    private function getProductsGenerator()
    {
        return Product::select('id', 'name', 'price')
                     ->where('status', 1)
                     ->cursor(); // Returns a generator
    }
    
    // Chunked processing for bulk operations
    public function updatePrices()
    {
        Product::where('status', 1)
               ->chunk(1000, function ($products) {
                   foreach ($products as $product) {
                       $product->update(['updated_at' => now()]);
                   }
               });
    }
}
```

## 📊 Monitoring & Profiling

### 1. Application Performance Monitoring
```php
// Custom performance middleware
class PerformanceMiddleware
{
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        $memoryUsage = round(($endMemory - $startMemory) / 1024 / 1024, 2);
        
        if ($executionTime > 1000) { // Log slow requests
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime . 'ms',
                'memory_usage' => $memoryUsage . 'MB',
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        // Add performance headers (development only)
        if (app()->environment('local')) {
            $response->headers->set('X-Execution-Time', $executionTime . 'ms');
            $response->headers->set('X-Memory-Usage', $memoryUsage . 'MB');
        }
        
        return $response;
    }
}
```

### 2. Database Query Monitoring
```php
// Service Provider
class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (app()->environment('local')) {
            DB::listen(function ($query) {
                if ($query->time > 1000) { // Log slow queries
                    Log::debug('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms',
                    ]);
                }
            });
        }
    }
}
```

### 3. Redis Performance Monitoring
```bash
#!/bin/bash
# redis-monitor.sh

# Check Redis memory usage
REDIS_MEMORY=$(redis-cli info memory | grep used_memory_human | cut -d: -f2 | tr -d '\r')
echo "Redis Memory Usage: $REDIS_MEMORY"

# Check hit rate
redis-cli info stats | grep keyspace_hits
redis-cli info stats | grep keyspace_misses

# Monitor slow commands
redis-cli slowlog get 10

# Check connected clients
redis-cli info clients | grep connected_clients
```

## 🎯 Performance Testing

### 1. Load Testing với Apache Bench
```bash
# Basic load test
ab -n 1000 -c 10 http://yourdomain.com/

# POST request testing
ab -n 1000 -c 10 -p post_data.txt -T application/x-www-form-urlencoded http://yourdomain.com/admin/login

# With authentication
ab -n 1000 -c 10 -C "session_cookie=value" http://yourdomain.com/admin/dashboard
```

### 2. Load Testing với Artillery
```yaml
# artillery-config.yml
config:
  target: 'http://yourdomain.com'
  phases:
    - duration: 60
      arrivalRate: 10
    - duration: 120
      arrivalRate: 20
    - duration: 60
      arrivalRate: 10

scenarios:
  - name: "Homepage Load Test"
    weight: 50
    flow:
      - get:
          url: "/"
      - think: 3

  - name: "Product Page Load Test"
    weight: 30
    flow:
      - get:
          url: "/category/dien-thoai.html"
      - think: 2
      - get:
          url: "/product/iphone-15-pro.html"

  - name: "Admin Panel Test"
    weight: 20
    flow:
      - post:
          url: "/admin/login"
          form:
            username: "admin"
            password: "password"
      - get:
          url: "/admin/dashboard"
```

### 3. Continuous Performance Monitoring
```bash
#!/bin/bash
# performance-check.sh

# Website response time
RESPONSE_TIME=$(curl -o /dev/null -s -w "%{time_total}" http://yourdomain.com)
echo "Response Time: ${RESPONSE_TIME}s"

# Check if response time is acceptable
if (( $(echo "$RESPONSE_TIME > 2.0" | bc -l) )); then
    echo "WARNING: Response time is above 2 seconds"
    # Send alert (email, Slack, etc.)
fi

# Database query performance
mysql -e "SHOW GLOBAL STATUS LIKE 'Slow_queries';"

# Memory usage
free -h

# CPU load
uptime
```

## 📈 Performance Optimization Checklist

### Database
- [ ] Proper indexing implemented
- [ ] Query optimization completed
- [ ] N+1 queries eliminated
- [ ] Database connection pooling configured
- [ ] Slow query logging enabled

### Caching
- [ ] Redis configured and optimized
- [ ] Application-level caching implemented
- [ ] HTTP caching headers set
- [ ] Cache invalidation strategy in place
- [ ] Cache warming implemented

### Frontend
- [ ] Assets minified and compressed
- [ ] Images optimized (WebP format)
- [ ] Lazy loading implemented
- [ ] Critical CSS inlined
- [ ] JavaScript bundled and optimized

### Server
- [ ] OPcache enabled and configured
- [ ] PHP-FPM optimized
- [ ] Gzip compression enabled
- [ ] CDN implemented
- [ ] HTTP/2 enabled

### Monitoring
- [ ] Performance monitoring in place
- [ ] Error tracking configured
- [ ] Load testing completed
- [ ] Performance alerts set up
- [ ] Regular performance audits scheduled
