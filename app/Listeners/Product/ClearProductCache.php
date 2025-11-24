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

            // Xóa cache slug resolution cho frontend (quan trọng!)
            CacheService::forgetTag(CacheService::TAGS['frontend']);

            // Xóa cache slug cụ thể nếu có thông tin
            if ($event->product && $event->product->id_product && $event->slug) {
                $slugCacheKey = "slug_resolution_{$event->product->id_product}_{$event->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $slugCacheKey);
            }

            // Xóa cache slug cũ nếu có (trường hợp slug thay đổi)
            if ($event->product && $event->product->slug && $event->product->slug !== $event->slug) {
                $oldSlugCacheKey = "slug_resolution_{$event->product->id_product}_{$event->product->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $oldSlugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'product_id' => $event->product?->id_product,
                'slug' => $event->slug,
            ]);
        }
    }
}
