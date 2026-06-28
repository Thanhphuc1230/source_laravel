<?php

namespace App\Listeners\Product;

use App\Events\Product\ProductChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearProductCache
{
    public function handle(ProductChanged $event): void
    {
        CacheService::forgetTags(['frontend', 'products']);
        if (!Cache::supportsTags()) {
            Cache::flush();
        }
    }
}
