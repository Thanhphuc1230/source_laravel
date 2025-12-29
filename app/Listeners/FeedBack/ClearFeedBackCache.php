<?php

namespace App\Listeners\FeedBack;

use App\Events\FeedBack\FeedbackChanged;
use Illuminate\Support\Facades\Log;

class ClearFeedBackCache
{
    public function handle(FeedBackChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
    }
}
