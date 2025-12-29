<?php

namespace App\Listeners\News;

use App\Events\News\NewsChanged;
use Illuminate\Support\Facades\Log;

class ClearNewsCache
{
    public function handle(NewsChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
    }
}
