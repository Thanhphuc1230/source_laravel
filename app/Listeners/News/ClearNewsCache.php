<?php

namespace App\Listeners\News;

use App\Events\News\NewsChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearNewsCache
{
    public function handle(NewsChanged $event): void
    {
        try {
            // Xóa cache news (tag-based)
            CacheService::forgetTag(CacheService::TAGS['news']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('news_cache');

            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
                CacheService::forget(CacheService::TAGS['news'], $slugCacheKey);
                Cache::forget($slugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear news cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug,
            ]);
        }
    }
}
