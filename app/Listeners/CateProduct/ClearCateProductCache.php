<?php

namespace App\Listeners\CateProduct;

use App\Events\CateProduct\CateProductChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearCateProductCache
{
    /**
     * Handle the event.
     */
    public function handle(CateProductChanged $event): void
    {
        try {
            // Xóa cache categories (tag-based)
            CacheService::forgetTag(CacheService::TAGS['categories']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('cate_product_cache');

            // Xóa cache slug nếu có
            if ($event->cateProduct && $event->cateProduct->slug) {
                $slugCacheKey = 'cate_product_' . $event->cateProduct->slug;
                Cache::forget($slugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear category product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}
