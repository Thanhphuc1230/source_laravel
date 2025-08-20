<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class LoginRateLimitService
{
    private int $maxAttempts;
    private int $decayMinutes;

    public function __construct(int $maxAttempts = 5, int $decayMinutes = 15)
    {
        $this->maxAttempts = $maxAttempts;
        $this->decayMinutes = $decayMinutes;
    }

    /**
     * Check if IP is rate limited
     */
    public function isBlocked(string $ip): bool
    {
        $attempts = $this->getAttempts($ip);
        return $attempts >= $this->maxAttempts;
    }

    /**
     * Increment failed attempts for IP
     */
    public function incrementAttempts(string $ip): void
    {
        $key = $this->getRateLimitKey($ip);
        $attempts = $this->getAttempts($ip);
        
        Cache::put($key, $attempts + 1, now()->addMinutes($this->decayMinutes));
    }

    /**
     * Clear attempts on successful login
     */
    public function clearAttempts(string $ip): void
    {
        Cache::forget($this->getRateLimitKey($ip));
    }

    /**
     * Get current attempts count
     */
    public function getAttempts(string $ip): int
    {
        return Cache::get($this->getRateLimitKey($ip), 0);
    }

    /**
     * Get rate limit error message
     */
    public function getErrorMessage(): string
    {
        return "Quá nhiều lần đăng nhập. Vui lòng thử lại trong {$this->decayMinutes} phút.";
    }

    /**
     * Get remaining time for blocked IP
     */
    public function getRemainingTime(string $ip): int
    {
        $key = $this->getRateLimitKey($ip);
        $expiresAt = Cache::get($key . '_expires');
        
        if (!$expiresAt) {
            return 0;
        }
        
        return max(0, $expiresAt - now()->timestamp);
    }

    /**
     * Generate cache key for IP
     */
    private function getRateLimitKey(string $ip): string
    {
        return "login_attempts_{$ip}";
    }
}
