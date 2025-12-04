<?php

namespace App\Listeners\Gallery;

use App\Events\Gallery\GalleryChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearGalleryCache
{
    public function handle(GalleryChanged $event): void
    {
        try {
            // Xóa cache gallery (tag-based)
            CacheService::forgetTag(CacheService::TAGS['galleries']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('gallery_cache');
        } catch (\Exception $e) {
            Log::error('Failed to clear gallery cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}