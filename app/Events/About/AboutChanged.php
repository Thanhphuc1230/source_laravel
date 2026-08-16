<?php

namespace App\Events\About;

use App\Models\About;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AboutChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $about;

    public $action; // 'created', 'updated', 'deleted', 'status_updated'

    public $slug_vn;
    public $slug_en;

    /**
     * Create a new event instance.
     */
    public function __construct($about = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->about = $about;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $about?->slug_vn;
        $this->slug_en = $slug_en ?? $about?->slug_en;
    }
}
