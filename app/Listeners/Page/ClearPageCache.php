<?php

namespace App\Listeners\Page;

use App\Events\Page\PageChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearPageCache
{
    public function handle(PageChanged $event): void
    {
        CacheService::forgetTags(['frontend', 'pages']);
        if (!Cache::supportsTags()) {
            Cache::flush();
        }
    }
}
