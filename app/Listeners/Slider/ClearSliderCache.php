<?php

namespace App\Listeners\Slider;

use App\Events\Slider\SliderChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearSliderCache
{
    public function handle(SliderChanged $event): void
    {
        try {
            // Xóa cache slider (tag-based)
            CacheService::forgetTag(CacheService::TAGS['sliders']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('slider_cache');
        } catch (\Exception $e) {
            Log::error('Failed to clear slider cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}
