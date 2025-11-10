<?php

namespace App\Listeners\Product;

use App\Events\Product\ProductChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearProductCache
{
    public function handle(ProductChanged $event): void
    {
        try {
            // Xóa cache products (tag-based)
            CacheService::forgetTag(CacheService::TAGS['products']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('product_cache');

            // Xóa cache slug nếu có
            if ($event->product && $event->product->slug) {
                $slugCacheKey = 'product_' . $event->product->slug;
                Cache::forget($slugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}
