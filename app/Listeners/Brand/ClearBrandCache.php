<?php

namespace App\Listeners\Brand;

use App\Events\Brand\BrandChanged;
use Illuminate\Support\Facades\Log;

class ClearBrandCache
{
    public function handle(BrandChanged $event): void
    {
        try {
            // Cache is now handled automatically by Cachable trait
            // No manual clearing needed
        } catch (\Exception $e) {
            Log::error('Failed to clear brand cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}