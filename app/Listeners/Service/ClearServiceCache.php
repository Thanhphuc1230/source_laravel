<?php

namespace App\Listeners\Service;

use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearServiceCache
{
    public function handle($event): void
    {
        CacheService::forgetTags(['frontend', 'service']);
        if (!Cache::supportsTags()) {
            Cache::flush();
        }
    }
}
