<?php

namespace App\Listeners\Product;

use App\Events\Product\ProductChanged;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearProductCache
{
    public function handle(ProductChanged $event): void
    {
        try {
            // Xóa cache product
            Cache::forget('product_cache');

            // Xóa cache slug resolution nếu có slug
            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
                Cache::forget($slugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug,
            ]);
        }
    }
}
