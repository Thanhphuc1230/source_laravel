<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class RateLimitService
{
    private int $maxAttempts;
    private int $decayMinutes;
    private string $prefix;

    public function __construct(string $prefix, int $maxAttempts = 5, int $decayMinutes = 15)
    {
        $this->prefix = $prefix;
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
     * Increment attempts for IP
     */
    public function incrementAttempts(string $ip): void
    {
        $key = $this->getRateLimitKey($ip);
        $attempts = $this->getAttempts($ip);
        
        Cache::put($key, $attempts + 1, now()->addMinutes($this->decayMinutes));
    }

    /**
     * Clear attempts on success
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
        return "Bạn đã thực hiện quá nhiều lần trong vòng {$this->decayMinutes} phút. Vui lòng thử lại sau.";
    }

    /**
     * Get remaining attempts
     */
    public function getRemainingAttempts(string $ip): int
    {
        return max(0, $this->maxAttempts - $this->getAttempts($ip));
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
        return "{$this->prefix}_attempts_{$ip}";
    }

    /**
     * Factory method for login rate limiting
     */
    public static function forLogin(int $maxAttempts = 5, int $decayMinutes = 15): self
    {
        return new self('login', $maxAttempts, $decayMinutes);
    }

    /**
     * Factory method for contact form rate limiting
     */
    public static function forContact(int $maxAttempts = 3, int $decayMinutes = 5): self
    {
        return new self('contact', $maxAttempts, $decayMinutes);
    }

    /**
     * Factory method for custom rate limiting
     */
    public static function for(string $prefix, int $maxAttempts = 5, int $decayMinutes = 15): self
    {
        return new self($prefix, $maxAttempts, $decayMinutes);
    }
}
