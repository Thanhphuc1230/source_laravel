<?php

namespace App\Listeners\News;

use App\Events\News\NewsChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearNewsCache
{
    public function handle(NewsChanged $event): void
    {
        CacheService::forgetTags(['frontend', 'news']);
        if (!Cache::supportsTags()) {
            Cache::flush();
        }
    }
}
