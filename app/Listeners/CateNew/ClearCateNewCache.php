<?php

namespace App\Listeners\CateNew;

use App\Events\CateNew\CateNewChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearCateNewCache
{
    public function handle(CateNewChanged $event): void
    {
        try {
            // Xóa cache cate_news (tag-based)
            CacheService::forgetTag(CacheService::TAGS['categories']);
            // Backward-compat: legacy key
            Cache::forget('cate_news_cache');

            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
                CacheService::forget(CacheService::TAGS['categories'], $slugCacheKey);
                Cache::forget($slugCacheKey);
            }

        } catch (\Exception $e) {
            Log::error('Failed to clear cate_news cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug,
            ]);
        }
    }
}
