<?php

namespace App\Listeners\About;

use App\Events\About\AboutChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class ClearAboutCache
{
    /**
     * Handle the event.
     */
    public function handle(AboutChanged $event): void
    {
        // Clear tags cache
        if (class_exists(CacheService::class)) {
            CacheService::forgetTags(['frontend', 'pages']);
        }
        
        if (!Cache::supportsTags()) {
            Cache::flush();
        }

        // Forget the route resolution cache for the specific old/new slugs
        if ($event->slug_vn) {
            Cache::forget("slug_resolution_{$event->slug_vn}");
        }
        if ($event->slug_en) {
            Cache::forget("slug_resolution_{$event->slug_en}");
        }
    }
}
