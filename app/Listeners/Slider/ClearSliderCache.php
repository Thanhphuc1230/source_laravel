<?php

namespace App\Listeners\Slider;

use App\Events\Slider\SliderChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearSliderCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(SliderChanged $event): void
    {
        try {
            // Xóa cache slider
            Cache::forget('slider_cache');
            
        } catch (\Exception $e) {
            Log::error('Failed to clear slider cache', [
                'error' => $e->getMessage(),
                'action' => $event->action
            ]);
        }
    }
} 