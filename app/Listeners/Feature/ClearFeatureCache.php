<?php

namespace App\Listeners\Feature;

use App\Events\Feature\FeatureChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearFeatureCache
{
    public function handle(FeatureChanged $event): void
    {
        try {
            // Xóa cache features (tag-based)
            CacheService::forgetTag(CacheService::TAGS['features']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('features_cache');
        } catch (\Exception $e) {
            Log::error('Failed to clear feature cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}