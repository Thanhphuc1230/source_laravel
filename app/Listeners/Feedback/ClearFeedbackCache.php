<?php

namespace App\Listeners\Feedback;

use App\Events\Feedback\FeedbackChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearFeedbackCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(FeedbackChanged $event): void
    {
        try {
            // Xóa cache feedback
            Cache::forget('feedback_cache');
            
            // Log activity
            Log::info('Feedback cache cleared', [
                'action' => $event->action,
                'feedback_id' => $event->feedback?->id,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to clear feedback cache', [
                'error' => $e->getMessage(),
                'action' => $event->action
            ]);
        }
    }
} 