<?php

namespace App\Listeners\News;

use App\Events\News\NewsChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearNewsCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(NewsChanged $event): void
    {
        try {
            // Xóa cache news
            Cache::forget('news_cache');
            
        } catch (\Exception $e) {
            Log::error('Failed to clear news cache', [
                'error' => $e->getMessage(),
                'action' => $event->action
            ]);
        }
    }
}