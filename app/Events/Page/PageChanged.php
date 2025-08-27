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

    public $slug; // Thêm slug để listener có thể xóa slug resolution cache

    /**
     * Create a new event instance.
     */
    public function __construct($page = null, string $action = 'changed', ?string $slug = null)
    {
        $this->page = $page;
        $this->action = $action;
        $this->slug = $slug ?? $page?->slug; // Auto-detect slug từ page
    }
}
