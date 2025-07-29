<?php

namespace App\Events\Page;

use App\Models\Page;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PageChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $page;
    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($page = null, string $action = 'changed')
    {
        $this->page = $page;
        $this->action = $action;
    }
}