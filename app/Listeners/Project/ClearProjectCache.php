<?php

namespace App\Listeners\Project;

use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearProjectCache
{
    public function handle($event): void
    {
        CacheService::forgetTags(['frontend', 'project']);
        if (!Cache::supportsTags()) {
            Cache::flush();
        }
    }
}
