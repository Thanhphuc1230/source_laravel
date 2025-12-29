<?php

namespace App\Listeners\Page;

use App\Events\Page\PageChanged;
use Illuminate\Support\Facades\Log;

class ClearPageCache
{
    public function handle(PageChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
    }
}
