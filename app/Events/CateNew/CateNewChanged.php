<?php

namespace App\Events\CateNew;

use App\Models\CateNew;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CateNewChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cateNew;

    public $action; // 'created', 'updated', 'deleted', 'status_updated'

    public $slug_vn;
    public $slug_en;

    /**
     * Create a new event instance.
     */
    public function __construct($cateNew = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->cateNew = $cateNew;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $cateNew?->slug_vn;
        $this->slug_en = $slug_en ?? $cateNew?->slug_en;
    }
}
