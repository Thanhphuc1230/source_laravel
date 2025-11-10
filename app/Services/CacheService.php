<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache tags for different modules
     */
    const TAGS = [
        'frontend' => 'frontend',
        'admin' => 'admin',
        'website' => 'website',
        'menu' => 'menu',
        'categories' => 'categories',
        'products' => 'products',
        'news' => 'news',
        'brands' => 'brands',
        'features' => 'features',
        'sliders' => 'sliders',
        'pages' => 'pages',
        'users' => 'users',
        'orders' => 'orders',
        'comments' => 'comments',
        'contacts' => 'contacts',
        'feedback' => 'feedback',
    ];

    /**
     * Default cache TTL in minutes
     */
    const TTL = [
        'short' => 15,    // 15 minutes
        'medium' => 60,   // 1 hour
        'long' => 360,    // 6 hours
        'day' => 1440,    // 24 hours
    ];

    /**
     * Remember cache with tags
     *
     * @param string $tag
     * @param string $key
     * @param int $ttl
     * @param callable $callback
     * @return mixed
     */
    public static function remember(string $tag, string $key, int $ttl, callable $callback)
    {
        return Cache::tags([$tag])->remember($key, $ttl, $callback);
    }

    /**
     * Forget cache by tag
     *
     * @param string $tag
     * @return bool
     */
    public static function forgetTag(string $tag): bool
    {
        return Cache::tags([$tag])->flush();
    }

    /**
     * Forget multiple tags
     *
     * @param array $tags
     * @return bool
     */
    public static function forgetTags(array $tags): bool
    {
        foreach ($tags as $tag) {
            self::forgetTag($tag);
        }
        return true;
    }

    /**
     * Forget cache by key in specific tag
     *
     * @param string $tag
     * @param string $key
     * @return bool
     */
    public static function forget(string $tag, string $key): bool
    {
        return Cache::tags([$tag])->forget($key);
    }

    /**
     * Get cache TTL by type
     *
     * @param string $type
     * @return int
     */
    public static function getTtl(string $type = 'medium'): int
    {
        return self::TTL[$type] ?? self::TTL['medium'];
    }

    /**
     * Clear all cache (use with caution)
     *
     * @return bool
     */
    public static function clearAll(): bool
    {
        return Cache::flush();
    }

    /**
     * Get cache size for a tag (if supported by cache driver)
     *
     * @param string $tag
     * @return int
     */
    public static function getTagSize(string $tag): int
    {
        try {
            $store = Cache::tags([$tag])->getStore();
            if (is_object($store) && method_exists($store, 'getSize')) {
                return (int) call_user_func([$store, 'getSize']);
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}