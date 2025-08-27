<?php

namespace App\Listeners\Page;

use App\Events\Page\PageChanged;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearPageCache
{
    public function handle(PageChanged $event): void
    {
        try {
            // Xóa cache page
            Cache::forget('page_cache');

            // Xóa cache slug resolution nếu có slug
            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
                Cache::forget($slugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear page cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug,
            ]);
        }
    }
}
