<?php

namespace App\Listeners\CateNew;

use App\Events\CateNew\CateNewChanged;
use Illuminate\Support\Facades\Log;

class ClearCateNewCache
{
    public function handle(CateNewChanged $event): void
    {
        try {
            // Cache is now handled automatically by Cachable trait
            // No manual clearing needed
        } catch (\Exception $e) {
            Log::error('Failed to clear cate_news cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'cate_new_id' => $event->cateNew?->id_cate_new,
                'slug' => $event->slug,
            ]);
        }
    }
}
