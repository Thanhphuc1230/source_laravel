<?php

namespace App\Events\Menu;

use App\Models\Menu;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $menu;

    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct(?Menu $menu = null, string $action = 'changed')
    {
        $this->menu = $menu;
        $this->action = $action;
    }
}
