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

            // Xóa cache slug nếu có
            if ($event->page && $event->page->slug) {
                $slugCacheKey = 'page_' . $event->page->slug;
                Cache::forget($slugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear page cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}
