<?php

namespace App\Listeners\CateProduct;

use App\Events\CateProduct\CateProductChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearCateProductCache
{
    public function handle(CateProductChanged $event): void
    {
        CacheService::forgetTags(['frontend', 'categories', 'products']);
        if (!Cache::supportsTags()) {
            Cache::flush();
        }
    }
}
