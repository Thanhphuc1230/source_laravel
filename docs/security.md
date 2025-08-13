# Security Guidelines

## 🔒 Tổng quan

Hướng dẫn bảo mật toàn diện cho Laravel Admin System. Bao gồm authentication, authorization, data protection, server hardening và incident response.

## 🛡️ Security Principles

### Defense in Depth
- **Multiple Security Layers**: Authentication, authorization, encryption, monitoring
- **Principle of Least Privilege**: Cấp quyền tối thiểu cần thiết
- **Fail Secure**: System fail về trạng thái an toàn
- **Regular Updates**: Cập nhật security patches thường xuyên

### Security by Design
- **Input Validation**: Validate tất cả user input
- **Output Encoding**: Encode output để tránh XSS
- **Secure Defaults**: Cấu hình mặc định an toàn
- **Error Handling**: Không leak sensitive information

## 🔐 Authentication & Authorization

### 1. Strong Authentication
```php
// config/auth.php - Secure authentication
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
        'table' => 'users',
    ],
],

// Strong password requirements
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

### 2. Password Security
```php
// User model with secure password handling
class User extends Authenticatable
{
    protected $fillable = [
        'username', 'email', 'fullname', 'password', 'level'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10 auto-hashing
    ];

    // Password validation rules
    public static function passwordRules()
    {
        return [
            'required',
            'string',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'confirmed',
        ];
    }
}
```

### 3. Session Security
```php
// config/session.php - Secure session configuration
return [
    'driver' => env('SESSION_DRIVER', 'redis'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => true,
    'encrypt' => true,
    'files' => storage_path('framework/sessions'),
    'connection' => 'cache',
    'table' => 'sessions',
    'store' => null,
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', 'laravel_session'),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN', null),
    'secure' => env('SESSION_SECURE_COOKIE', true), // HTTPS only
    'http_only' => true, // Prevent XSS
    'same_site' => 'strict', // CSRF protection
];
```

### 4. Multi-Factor Authentication
```php
// MFA Implementation
class TwoFactorAuthService
{
    public function generateSecret($user)
    {
        $secret = $this->generateRandomSecret();
        $user->update(['two_factor_secret' => encrypt($secret)]);
        
        return $secret;
    }
    
    public function generateQRCode($user, $secret)
    {
        $appName = config('app.name');
        $qrCodeUrl = "otpauth://totp/{$appName}:{$user->email}?secret={$secret}&issuer={$appName}";
        
        return QrCode::size(200)->generate($qrCodeUrl);
    }
    
    public function verifyToken($user, $token)
    {
        $secret = decrypt($user->two_factor_secret);
        $google2fa = new Google2FA();
        
        return $google2fa->verifyKey($secret, $token);
    }
}

// Middleware for 2FA
class TwoFactorMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        
        if ($user->two_factor_enabled && !session('2fa_verified')) {
            return redirect()->route('2fa.verify');
        }
        
        return $next($request);
    }
}
```

## 🔒 Input Validation & Sanitization

### 1. Form Request Validation
```php
// Enhanced validation rules
class SecureProductRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage', 'products');
    }
    
    public function rules()
    {
        return [
            'name_vn' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\p{L}]+$/u', // Alphanumeric + unicode
            ],
            'content_vn' => [
                'required',
                'string',
                'max:50000',
                function ($attribute, $value, $fail) {
                    if ($this->containsMaliciousContent($value)) {
                        $fail('Content contains potentially malicious code.');
                    }
                },
            ],
            'image' => [
                'nullable',
                'file',
                'mimes:jpeg,jpg,png,webp',
                'max:5120', // 5MB
                'dimensions:min_width=100,min_height=100,max_width=3000,max_height=3000',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('tp_products', 'slug')->ignore($this->route('uuid'), 'uuid'),
            ],
        ];
    }
    
    private function containsMaliciousContent($content)
    {
        $maliciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/data:/i',
            '/on\w+\s*=/i',
        ];
        
        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }
    
    protected function prepareForValidation()
    {
        $this->merge([
            'name_vn' => strip_tags($this->name_vn),
            'slug' => Str::slug($this->slug ?: $this->name_vn),
        ]);
    }
}
```

### 2. SQL Injection Prevention
```php
// Always use parameterized queries
class SecureProductService
{
    public function search($query, $categoryId = null)
    {
        $builder = Product::query();
        
        // ✅ Safe parameterized query
        if ($query) {
            $builder->where('name_vn', 'LIKE', '%' . $query . '%');
            // Or use full-text search
            $builder->whereRaw('MATCH(name_vn, content_vn) AGAINST(? IN NATURAL LANGUAGE MODE)', [$query]);
        }
        
        if ($categoryId) {
            $builder->where('category_id', '=', $categoryId);
        }
        
        return $builder->get();
    }
    
    // ❌ Never use raw SQL with user input
    // DB::select("SELECT * FROM products WHERE name LIKE '%{$userInput}%'");
}
```

### 3. XSS Prevention
```php
// Output encoding helper
function secureOutput($content, $allowedTags = [])
{
    if (empty($allowedTags)) {
        return e($content); // Laravel's built-in escaping
    }
    
    // Allow specific HTML tags
    $allowedTagsString = '<' . implode('><', $allowedTags) . '>';
    return strip_tags($content, $allowedTagsString);
}

// Content sanitization
class ContentSanitizer
{
    private $allowedTags = [
        'p', 'br', 'strong', 'em', 'u', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li', 'a', 'img', 'blockquote', 'table', 'tr', 'td', 'th'
    ];
    
    private $allowedAttributes = [
        'a' => ['href', 'title'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
    ];
    
    public function sanitize($content)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', implode(',', $this->allowedTags));
        
        foreach ($this->allowedAttributes as $tag => $attributes) {
            $config->set("HTML.AllowedAttributes.{$tag}", implode(',', $attributes));
        }
        
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($content);
    }
}
```

## 🛡️ CSRF Protection

### 1. CSRF Token Validation
```php
// Middleware configuration
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];

// CSRF exception for API endpoints
class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'api/*', // API routes excluded
        'webhooks/*', // Webhook endpoints
    ];
}
```

### 2. AJAX CSRF Protection
```javascript
// Setup CSRF token for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Secure AJAX request
function secureAjaxRequest(url, data, method = 'POST') {
    return $.ajax({
        url: url,
        method: method,
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json'
    });
}
```

## 🔐 File Upload Security

### 1. Secure File Upload
```php
class SecureFileUploadService
{
    private $allowedMimeTypes = [
        'image/jpeg',
        'image/png', 
        'image/gif',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    
    private $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    public function validateFile(UploadedFile $file)
    {
        $errors = [];
        
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            $errors[] = 'File size exceeds maximum allowed size.';
        }
        
        // Check MIME type
        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            $errors[] = 'File type not allowed.';
        }
        
        // Check file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            $errors[] = 'File extension not allowed.';
        }
        
        // Verify file content matches extension
        if (!$this->verifyFileContent($file)) {
            $errors[] = 'File content does not match extension.';
        }
        
        // Scan for malware (if antivirus available)
        if ($this->containsMalware($file)) {
            $errors[] = 'File contains malware.';
        }
        
        return $errors;
    }
    
    private function verifyFileContent(UploadedFile $file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file->getPathname());
        finfo_close($finfo);
        
        return $mimeType === $file->getMimeType();
    }
    
    private function containsMalware(UploadedFile $file)
    {
        // Integration with ClamAV or similar
        $command = "clamscan " . escapeshellarg($file->getPathname());
        exec($command, $output, $returnCode);
        
        return $returnCode !== 0;
    }
    
    public function secureUpload(UploadedFile $file, $directory)
    {
        $errors = $this->validateFile($file);
        
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        
        // Generate secure filename
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(40) . '.' . $extension;
        
        // Store file outside public directory
        $path = $file->storeAs($directory, $filename, 'secure');
        
        return $path;
    }
}
```

### 2. Image Processing Security
```php
class SecureImageProcessor
{
    public function processImage($imagePath)
    {
        // Re-encode image to remove potential embedded code
        $imageInfo = getimagesize($imagePath);
        
        if (!$imageInfo) {
            throw new InvalidArgumentException('Invalid image file');
        }
        
        [$width, $height, $imageType] = $imageInfo;
        
        // Load image based on type
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($imagePath);
                break;
            default:
                throw new InvalidArgumentException('Unsupported image type');
        }
        
        // Create new clean image
        $cleanImage = imagecreatetruecolor($width, $height);
        imagecopy($cleanImage, $image, 0, 0, 0, 0, $width, $height);
        
        // Save clean image
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($cleanImage, $imagePath, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($cleanImage, $imagePath);
                break;
            case IMAGETYPE_GIF:
                imagegif($cleanImage, $imagePath);
                break;
        }
        
        imagedestroy($image);
        imagedestroy($cleanImage);
        
        return true;
    }
}
```

## 🌐 HTTP Security Headers

### 1. Security Headers Middleware
```php
class SecurityHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Content Type Sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net; " .
               "img-src 'self' data: *.googleapis.com; " .
               "font-src 'self' fonts.gstatic.com; " .
               "connect-src 'self'; " .
               "frame-ancestors 'self'";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // HSTS (only for HTTPS)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        // Remove server information
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        
        return $response;
    }
}
```

### 2. CORS Configuration
```php
// config/cors.php
return [
    'paths' => ['api/*', 'admin/api/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'allowed_origins' => [
        'https://yourdomain.com',
        'https://admin.yourdomain.com',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'X-CSRF-TOKEN'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

## 🔍 Security Monitoring & Logging

### 1. Security Event Logging
```php
class SecurityLogger
{
    public static function logFailedLogin($request, $username)
    {
        Log::channel('security')->warning('Failed login attempt', [
            'username' => $username,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);
        
        // Rate limiting check
        $key = 'failed_login:' . $request->ip();
        $attempts = Redis::incr($key);
        Redis::expire($key, 300); // 5 minutes
        
        if ($attempts > 5) {
            $this->blockIP($request->ip());
        }
    }
    
    public static function logSuspiciousActivity($activity, $details = [])
    {
        Log::channel('security')->alert('Suspicious activity detected', array_merge([
            'activity' => $activity,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now(),
        ], $details));
    }
    
    public static function logDataAccess($model, $action)
    {
        Log::channel('audit')->info('Data access', [
            'model' => get_class($model),
            'model_id' => $model->getKey(),
            'action' => $action,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now(),
        ]);
    }
}
```

### 2. Intrusion Detection
```php
class IntrusionDetectionService
{
    private $suspiciousPatterns = [
        '/\bunion\s+select/i',
        '/\bselect\b.*\bfrom\b.*\bwhere\b/i',
        '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
        '/javascript:/i',
        '/vbscript:/i',
        '/\.\.\/.*\.\.\//',
        '/\.(php|asp|jsp|cgi)\?/i',
    ];
    
    public function analyzeRequest($request)
    {
        $content = json_encode($request->all());
        $url = $request->fullUrl();
        
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content) || preg_match($pattern, $url)) {
                $this->reportThreat($request, $pattern);
                return true;
            }
        }
        
        return false;
    }
    
    private function reportThreat($request, $pattern)
    {
        SecurityLogger::logSuspiciousActivity('Pattern match detected', [
            'pattern' => $pattern,
            'request_data' => $request->all(),
            'url' => $request->fullUrl(),
        ]);
        
        // Block IP if needed
        $this->considerIPBlocking($request->ip());
    }
}
```

### 3. Rate Limiting
```php
// Enhanced rate limiting
class AdminRateLimitMiddleware
{
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            // Log potential attack
            SecurityLogger::logSuspiciousActivity('Rate limit exceeded', [
                'ip' => $request->ip(),
                'attempts' => RateLimiter::attempts($key),
                'retry_after' => $seconds,
            ]);
            
            return response('Too Many Requests', 429)
                ->header('Retry-After', $seconds);
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        return $next($request);
    }
    
    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip()
        );
    }
}
```

## 🔐 Data Encryption

### 1. Database Encryption
```php
// Encrypted model attributes
class User extends Model
{
    protected $fillable = ['email', 'phone', 'personal_data'];
    
    protected $casts = [
        'personal_data' => 'encrypted',
        'phone' => 'encrypted',
    ];
    
    // Custom encryption for sensitive fields
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = encrypt($value);
    }
    
    public function getPhoneAttribute($value)
    {
        return decrypt($value);
    }
}
```

### 2. File Encryption
```php
class FileEncryptionService
{
    public function encryptFile($filePath, $key = null)
    {
        $key = $key ?: config('app.key');
        $data = file_get_contents($filePath);
        $encrypted = encrypt($data);
        
        file_put_contents($filePath . '.enc', $encrypted);
        unlink($filePath); // Remove original
        
        return $filePath . '.enc';
    }
    
    public function decryptFile($encryptedPath, $key = null)
    {
        $key = $key ?: config('app.key');
        $encryptedData = file_get_contents($encryptedPath);
        $decrypted = decrypt($encryptedData);
        
        $originalPath = str_replace('.enc', '', $encryptedPath);
        file_put_contents($originalPath, $decrypted);
        
        return $originalPath;
    }
}
```

## 🚨 Incident Response

### 1. Security Incident Detection
```php
class SecurityIncidentResponse
{
    public function handleSecurityIncident($type, $details)
    {
        // Log incident
        Log::channel('security')->emergency('Security incident detected', [
            'type' => $type,
            'details' => $details,
            'timestamp' => now(),
        ]);
        
        // Immediate response actions
        switch ($type) {
            case 'sql_injection':
                $this->blockSuspiciousIP($details['ip']);
                $this->notifyAdministrators('SQL Injection Attempt', $details);
                break;
                
            case 'file_upload_threat':
                $this->quarantineFile($details['file_path']);
                $this->notifyAdministrators('Malicious File Upload', $details);
                break;
                
            case 'brute_force':
                $this->implementIPBan($details['ip']);
                $this->notifyAdministrators('Brute Force Attack', $details);
                break;
        }
        
        // Create incident ticket
        $this->createIncidentTicket($type, $details);
    }
    
    private function blockSuspiciousIP($ip)
    {
        Redis::setex("blocked_ip:{$ip}", 3600, 1); // Block for 1 hour
        
        // Add to firewall if available
        exec("ufw deny from {$ip}");
    }
    
    private function notifyAdministrators($subject, $details)
    {
        $admins = User::where('level', 1)->get();
        
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new SecurityAlertMail($subject, $details));
        }
        
        // Slack notification if configured
        if (config('services.slack.webhook_url')) {
            $this->sendSlackAlert($subject, $details);
        }
    }
}
```

### 2. Backup & Recovery
```php
class SecurityBackupService
{
    public function createSecurityBackup()
    {
        $backupData = [
            'users' => User::all(),
            'audit_logs' => DB::table('audit_logs')->get(),
            'security_logs' => $this->getSecurityLogs(),
            'system_config' => $this->getSystemConfig(),
        ];
        
        $encrypted = encrypt(json_encode($backupData));
        $filename = 'security_backup_' . date('Y-m-d_H-i-s') . '.enc';
        
        Storage::disk('secure')->put($filename, $encrypted);
        
        return $filename;
    }
    
    public function restoreFromBackup($filename)
    {
        $encrypted = Storage::disk('secure')->get($filename);
        $data = json_decode(decrypt($encrypted), true);
        
        DB::transaction(function () use ($data) {
            // Restore users
            foreach ($data['users'] as $userData) {
                User::updateOrCreate(['id' => $userData['id']], $userData);
            }
            
            // Restore logs
            foreach ($data['audit_logs'] as $logData) {
                DB::table('audit_logs')->updateOrInsert(['id' => $logData['id']], $logData);
            }
        });
    }
}
```

## 🔧 Server Security Hardening

### 1. System Hardening Script
```bash
#!/bin/bash
# security-hardening.sh

echo "🔒 Starting security hardening..."

# Update system
apt update && apt upgrade -y

# Install security tools
apt install -y fail2ban ufw unattended-upgrades

# Configure automatic updates
echo 'Unattended-Upgrade::Automatic-Reboot "true";' >> /etc/apt/apt.conf.d/50unattended-upgrades
systemctl enable unattended-upgrades

# Configure fail2ban
cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local

cat >> /etc/fail2ban/jail.local << EOF
[sshd]
enabled = true
maxretry = 3
bantime = 3600

[nginx-limit-req]
enabled = true
filter = nginx-limit-req
action = iptables-multiport[name=ReqLimit, port="http,https", protocol=tcp]
logpath = /var/log/nginx/error.log
maxretry = 10
findtime = 600
bantime = 7200
EOF

# Configure firewall
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 'Nginx Full'
ufw --force enable

# Secure SSH
sed -i 's/#PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config
sed -i 's/#PasswordAuthentication yes/PasswordAuthentication no/' /etc/ssh/sshd_config
systemctl restart ssh

# Set file permissions
chmod 600 /etc/ssh/sshd_config
chmod 600 /var/www/laravel-admin/.env

echo "✅ Security hardening completed!"
```

### 2. SSL/TLS Configuration
```nginx
# Nginx SSL configuration
server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    
    # SSL Certificates
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/private.key;
    
    # SSL Configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    
    # OCSP Stapling
    ssl_stapling on;
    ssl_stapling_verify on;
    ssl_trusted_certificate /path/to/ca-certs.pem;
    resolver 8.8.8.8 8.8.4.4 valid=300s;
    resolver_timeout 5s;
    
    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
}
```

## 📋 Security Checklist

### Application Security
- [ ] Input validation implemented
- [ ] SQL injection protection in place
- [ ] XSS prevention configured
- [ ] CSRF protection enabled
- [ ] File upload security implemented
- [ ] Authentication strengthened
- [ ] Authorization properly configured
- [ ] Session security hardened

### Server Security
- [ ] SSL/TLS properly configured
- [ ] Security headers implemented
- [ ] Firewall configured
- [ ] Fail2ban installed and configured
- [ ] SSH hardened
- [ ] File permissions set correctly
- [ ] Automatic updates enabled
- [ ] Intrusion detection active

### Monitoring & Response
- [ ] Security logging implemented
- [ ] Monitoring alerts configured
- [ ] Incident response plan ready
- [ ] Backup strategy in place
- [ ] Security auditing enabled
- [ ] Rate limiting configured
- [ ] Regular security scans scheduled

### Compliance & Documentation
- [ ] Security policies documented
- [ ] Access controls documented
- [ ] Incident response procedures written
- [ ] Security training provided
- [ ] Regular security reviews scheduled
- [ ] Penetration testing planned
