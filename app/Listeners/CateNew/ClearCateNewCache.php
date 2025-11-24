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

            // Xóa cache slug resolution cho frontend (quan trọng!)
            CacheService::forgetTag(CacheService::TAGS['frontend']);

            // Xóa cache slug cụ thể nếu có thông tin
            if ($event->cateNew && $event->cateNew->id_cate_new && $event->slug) {
                $slugCacheKey = "slug_resolution_{$event->cateNew->id_cate_new}_{$event->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $slugCacheKey);
            }

            // Xóa cache slug cũ nếu có (trường hợp slug thay đổi)
            if ($event->cateNew && $event->cateNew->slug && $event->cateNew->slug !== $event->slug) {
                $oldSlugCacheKey = "slug_resolution_{$event->cateNew->id_cate_new}_{$event->cateNew->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $oldSlugCacheKey);
            }

        } catch (\Exception $e) {
            Log::error('Failed to clear cate_news cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'cate_new_id' => $event->cateNew?->id_cate_new,
                'slug' => $event->slug,
            ]);
        }
    }
}
