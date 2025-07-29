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
            // Xóa cache cate_news
            Cache::forget('cate_news_cache');
            
            // Xóa cache slug resolution nếu có slug
            if ($event->slug) {
                $slugCacheKey = "slug_resolution_{$event->slug}";
                Cache::forget($slugCacheKey);
                
                Log::info('Cleared slug resolution cache', [
                    'slug' => $event->slug,
                    'action' => $event->action
                ]);
            }
            
            Log::info('Cleared cate_news cache', [
                'action' => $event->action,
                'cate_news_id' => $event->cateNew?->id_cate_new
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to clear cate_news cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
                'slug' => $event->slug
            ]);
        }
    }
}