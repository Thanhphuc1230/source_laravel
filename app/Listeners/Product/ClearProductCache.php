<?php

namespace App\Listeners\Product;

use App\Events\Product\ProductChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearProductCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ProductChanged $event): void
    {
        try {
            // Xóa cache product
            Cache::forget('product_cache');
            
            // Xóa cache slug resolution nếu có slug
            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
                Cache::forget($slugCacheKey);
                
                Log::info('Cleared slug resolution cache', [
                    'slug' => $event->slug,
                    'action' => $event->action
                ]);
            }
            
            Log::info('Cleared product cache', [
                'action' => $event->action,
                'product_id' => $event->product?->id_product
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to clear product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug
            ]);
        }
    }
}