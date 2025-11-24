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

            // Xóa cache slug resolution cho frontend (quan trọng!)
            CacheService::forgetTag(CacheService::TAGS['frontend']);

            // Xóa cache slug cụ thể nếu có thông tin
            if ($event->cateProduct && $event->cateProduct->id_cate_product && $event->slug) {
                $slugCacheKey = "slug_resolution_{$event->cateProduct->id_cate_product}_{$event->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $slugCacheKey);
            }

            // Xóa cache slug cũ nếu có (trường hợp slug thay đổi)
            if ($event->cateProduct && $event->cateProduct->slug && $event->cateProduct->slug !== $event->slug) {
                $oldSlugCacheKey = "slug_resolution_{$event->cateProduct->id_cate_product}_{$event->cateProduct->slug}";
                CacheService::forget(CacheService::TAGS['frontend'], $oldSlugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear category product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'cate_product_id' => $event->cateProduct?->id_cate_product,
                'slug' => $event->slug,
            ]);
        }
    }
}
