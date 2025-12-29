<?php

namespace App\Listeners\CateProduct;

use App\Events\CateProduct\CateProductChanged;
use Illuminate\Support\Facades\Log;

class ClearCateProductCache
{
    /**
     * Handle the event.
     */
    public function handle(CateProductChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
    }
}
