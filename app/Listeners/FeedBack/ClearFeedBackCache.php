<?php

namespace App\Listeners\FeedBack;

use App\Events\FeedBack\FeedbackChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearFeedBackCache
{
    public function handle(FeedBackChanged $event): void
    {
        try {
            // Xóa cache feedback (tag-based)
            CacheService::forgetTag(CacheService::TAGS['feedback']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('feedback_cache');
        } catch (\Exception $e) {
            Log::error('Failed to clear feedback cache', [
                'error' => $e->getMessage(),
                'event' => $event,
            ]);
        }
    }
}
