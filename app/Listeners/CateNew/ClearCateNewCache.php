<?php

namespace App\Listeners\CateNew;

use App\Events\CateNew\CateNewChanged;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearCateNewCache
{
    public function handle(CateNewChanged $event): void
    {
        try {
            // Xóa cache cate_news
            Cache::forget('cate_news_cache');

            // Xóa cache slug resolution nếu có slug
            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
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
