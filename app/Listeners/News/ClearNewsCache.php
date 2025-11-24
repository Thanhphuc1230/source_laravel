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

            // Xóa cache slug resolution cho frontend (quan trọng!)
            CacheService::forgetTag(CacheService::TAGS['frontend']);

            // Xóa cache slug cụ thể nếu có thông tin
            if ($event->news && $event->news->id_new && $event->slug) {
                $slugCacheKey = "slug_resolution_{$event->news->id_new}_{$event->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $slugCacheKey);
            }

            // Xóa cache slug cũ nếu có (trường hợp slug thay đổi)
            if ($event->news && $event->news->slug && $event->news->slug !== $event->slug) {
                $oldSlugCacheKey = "slug_resolution_{$event->news->id_new}_{$event->news->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $oldSlugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear news cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'news_id' => $event->news?->id_new,
                'slug' => $event->slug,
            ]);
        }
    }
}
