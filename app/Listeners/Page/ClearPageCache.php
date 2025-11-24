<?php

namespace App\Listeners\Page;

use App\Events\Page\PageChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearPageCache
{
    public function handle(PageChanged $event): void
    {
        try {
            // Xóa cache pages (tag-based)
            CacheService::forgetTag(CacheService::TAGS['pages']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('page_cache');

            // Xóa cache slug resolution cho frontend (quan trọng!)
            CacheService::forgetTag(CacheService::TAGS['frontend']);

            // Xóa cache slug cụ thể nếu có thông tin
            if ($event->page && $event->page->id_page && $event->slug) {
                $slugCacheKey = "slug_resolution_{$event->page->id_page}_{$event->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $slugCacheKey);
            }

            // Xóa cache slug cũ nếu có (trường hợp slug thay đổi)
            if ($event->page && $event->page->slug && $event->page->slug !== $event->slug) {
                $oldSlugCacheKey = "slug_resolution_{$event->page->id_page}_{$event->page->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $oldSlugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear page cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'page_id' => $event->page?->id_page,
                'slug' => $event->slug,
            ]);
        }
    }
}
