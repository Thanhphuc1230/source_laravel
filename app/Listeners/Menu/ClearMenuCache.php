<?php

namespace App\Listeners\Menu;

use App\Events\Menu\MenuChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearMenuCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(MenuChanged $event): void
    {
        try {
            // Xóa cache menu header
            Cache::forget('menu_header');
        } catch (\Exception $e) {
            Log::error('Failed to clear menu cache', [
                'error' => $e->getMessage(),
                'action' => $event->action
            ]);
        }
    }
} 