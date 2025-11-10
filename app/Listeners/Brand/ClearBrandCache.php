<?php

namespace App\Listeners\Brand;

use App\Events\Brand\BrandChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearBrandCache
{
    public function handle(BrandChanged $event): void
    {
        try {
            // Xóa cache brands (tag-based)
            CacheService::forgetTag(CacheService::TAGS['brands']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('brands_cache');
        } catch (\Exception $e) {
            Log::error('Failed to clear brand cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}