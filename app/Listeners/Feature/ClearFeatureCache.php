<?php

namespace App\Listeners\Feature;

use App\Events\Feature\FeatureChanged;
use Illuminate\Support\Facades\Log;

class ClearFeatureCache
{
    public function handle(FeatureChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
    }
}