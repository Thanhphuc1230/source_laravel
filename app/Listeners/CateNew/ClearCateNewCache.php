<?php

namespace App\Listeners\CateNew;

use App\Events\CateNew\CateNewChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearCateNewCache
{
    public function handle(CateNewChanged $event): void
    {
        CacheService::forgetTags(['frontend', 'categories', 'news']);
        if (!Cache::supportsTags()) {
            Cache::flush();
        }
    }
}
