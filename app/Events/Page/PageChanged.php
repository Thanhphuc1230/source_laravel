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

    public $action; // 'created', 'updated', 'deleted', 'status_updated'

    public $slug_vn;
    public $slug_en;

    /**
     * Create a new event instance.
     */
    public function __construct($page = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->page = $page;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $page?->slug_vn;
        $this->slug_en = $slug_en ?? $page?->slug_en;
    }
}
