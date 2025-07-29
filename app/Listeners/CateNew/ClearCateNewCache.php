<?php

namespace App\Listeners\CateNew;

use App\Events\CateNew\CateNewChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearCateNewCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(CateNewChanged $event): void
    {
        try {
            // Xóa cache cate_new
            Cache::forget('cate_new_cache');
            
        } catch (\Exception $e) {
            Log::error('Failed to clear cate_new cache', [
                'error' => $e->getMessage(),
                'action' => $event->action
            ]);
        }
    }
}