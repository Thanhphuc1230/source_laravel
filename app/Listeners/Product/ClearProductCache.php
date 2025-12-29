<?php

namespace App\Listeners\Product;

use App\Events\Product\ProductChanged;
use Illuminate\Support\Facades\Log;

class ClearProductCache
{
    public function handle(ProductChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
        // Keeping for potential future use or custom caches
    }
}
