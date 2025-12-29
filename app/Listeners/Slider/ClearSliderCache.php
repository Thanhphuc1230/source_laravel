<?php

namespace App\Listeners\Slider;

use App\Events\Slider\SliderChanged;
use Illuminate\Support\Facades\Log;

class ClearSliderCache
{
    public function handle(SliderChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
    }
}
