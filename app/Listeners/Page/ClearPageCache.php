<?php

namespace App\Listeners\Page;

use App\Events\Page\PageChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearPageCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(PageChanged $event): void
    {
        try {
            // Xóa cache page
            Cache::forget('page_cache');
            
            // Xóa cache slug resolution nếu có slug
            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
                Cache::forget($slugCacheKey);
                
                Log::info('Cleared slug resolution cache', [
                    'slug' => $event->slug,
                    'action' => $event->action
                ]);
            }
            
            Log::info('Cleared page cache', [
                'action' => $event->action,
                'page_id' => $event->page?->id_page
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to clear page cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug
            ]);
        }
    }
}