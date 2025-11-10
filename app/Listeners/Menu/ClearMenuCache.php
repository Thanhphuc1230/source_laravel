<?php

namespace App\Listeners\Menu;

use App\Events\Menu\MenuChanged;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearMenuCache
{
    public function handle(MenuChanged $event): void
    {
        try {
            // Xóa cache menu (tag-based)
            CacheService::forgetTag(CacheService::TAGS['menu']);
            // Backward-compat: also try to forget legacy key
            Cache::forget('menu_header');
        } catch (\Exception $e) {
            Log::error('Failed to clear menu cache', [
                'error' => $e->getMessage(),
                'action' => $event->action,
            ]);
        }
    }
}
