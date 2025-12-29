<?php

namespace App\Listeners\Menu;

use App\Events\Menu\MenuChanged;
use Illuminate\Support\Facades\Log;

class ClearMenuCache
{
    public function handle(MenuChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
    }
}
