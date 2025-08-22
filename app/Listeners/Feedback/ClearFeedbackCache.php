<?php

namespace App\Listeners\Feedback;

use App\Events\Feedback\FeedbackChanged;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearFeedbackCache
{
    public function handle(FeedbackChanged $event): void
    {
        try {
            // Xóa cache feedback
            Cache::forget('feedback_cache');
        } catch (\Exception $e) {
            Log::error('Failed to clear feedback cache', [
                'error' => $e->getMessage(),
                'action' => $event->action
            ]);
        }
    }
} 