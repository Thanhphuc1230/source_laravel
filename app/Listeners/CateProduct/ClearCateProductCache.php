<?php

namespace App\Listeners\CateProduct;

use App\Events\CateProduct\CateProductChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearCateProductCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(CateProductChanged $event): void
    {
        try {
            // Xóa cache cate_product
            Cache::forget('cate_product_cache');

            // Xóa cache slug resolution nếu có slug
            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
                Cache::forget($slugCacheKey);
            }
        } catch (\Exception $e) {
            Log::error('Failed to clear cate_product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug
            ]);
        }
    }
}
