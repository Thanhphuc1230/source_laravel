<?php

namespace App\Listeners\Menu;

use App\Events\Menu\MenuChanged;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearMenuCache
{
    public function handle(MenuChanged $event): void
    {
        try {
            // Xóa cache menu
            Cache::forget('menu_cache');
        } catch (\Exception $e) {
            Log::error('Failed to clear menu cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}
