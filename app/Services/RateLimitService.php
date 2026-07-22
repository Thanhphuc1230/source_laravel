<?php

namespace App\Services;

use Illuminate\Support\Facades\RateLimiter;

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
     * Lấy cache key cho rate limit
     */
    private function getRateLimitKey(string $key): string
    {
        return "{$this->prefix}_{$key}";
    }

    /**
     * Check if key is rate limited
     */
    public function isBlocked(string $key): bool
    {
        return RateLimiter::tooManyAttempts($this->getRateLimitKey($key), $this->maxAttempts);
    }

    /**
     * Increment attempts for key
     */
    public function incrementAttempts(string $key): void
    {
        RateLimiter::hit($this->getRateLimitKey($key), $this->decayMinutes * 60);
    }

    /**
     * Clear attempts on success
     */
    public function clearAttempts(string $key): void
    {
        RateLimiter::clear($this->getRateLimitKey($key));
    }

    /**
     * Get current attempts count
     */
    public function getAttempts(string $key): int
    {
        return RateLimiter::attempts($this->getRateLimitKey($key));
    }

    /**
     * Get rate limit error message
     */
    public function getErrorMessage(string $key = ''): string
    {
        $seconds = 0;
        if (!empty($key)) {
            $seconds = RateLimiter::availableIn($this->getRateLimitKey($key));
        }
        
        $minutes = ceil($seconds / 60);
        $minutes = $minutes > 0 ? $minutes : $this->decayMinutes;

        if ($this->prefix === 'contact') {
            return "Bạn đã gửi quá nhiều yêu cầu liên hệ. Vui lòng thử lại sau {$minutes} phút.";
        }

        return "Bạn đã nhập sai quá {$this->maxAttempts} lần. Vui lòng thử lại sau {$minutes} phút.";
    }

    /**
     * Get remaining attempts
     */
    public function getRemainingAttempts(string $key): int
    {
        return RateLimiter::remaining($this->getRateLimitKey($key), $this->maxAttempts);
    }

    /**
     * Get remaining time for blocked key in seconds
     */
    public function getRemainingTime(string $key): int
    {
        return RateLimiter::availableIn($this->getRateLimitKey($key));
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
     * Factory method for cart operations rate limiting
     */
    public static function forCart(int $maxAttempts = 10, int $decayMinutes = 1): self
    {
        return new self('cart', $maxAttempts, $decayMinutes);
    }

    /**
     * Factory method for checkout operations rate limiting
     */
    public static function forCheckout(int $maxAttempts = 3, int $decayMinutes = 10): self
    {
        return new self('checkout', $maxAttempts, $decayMinutes);
    }

    /**
     * Factory method for content access rate limiting
     */
    public static function forContent(int $maxAttempts = 100, int $decayMinutes = 1): self
    {
        return new self('content', $maxAttempts, $decayMinutes);
    }
}
