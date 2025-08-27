<?php

namespace App\Listeners\News;

use App\Events\News\NewsChanged;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearNewsCache
{
    public function handle(NewsChanged $event): void
    {
        try {
            // Xóa cache news
            Cache::forget('news_cache');

            // Xóa cache slug resolution nếu có slug
            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
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
