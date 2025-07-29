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
            
        } catch (\Exception $e) {
            Log::error('Failed to clear product cache', [
                'error' => $e->getMessage(),
                'action' => $event->action
            ]);
        }
    }
}