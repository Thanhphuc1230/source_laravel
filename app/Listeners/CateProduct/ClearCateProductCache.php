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
                
                Log::info('Cleared slug resolution cache', [
                    'slug' => $event->slug,
                    'action' => $event->action
                ]);
            }
            
            Log::info('Cleared cate_product cache', [
                'action' => $event->action,
                'cate_product_id' => $event->cateProduct?->id_cate_product
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to clear cate_product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug
            ]);
        }
    }
}