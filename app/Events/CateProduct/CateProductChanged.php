<?php

namespace App\Events\CateProduct;

use App\Models\CateProduct;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CateProductChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cateProduct;

    public $action; // 'created', 'updated', 'deleted', 'status_updated'

    public $slug_vn;
    public $slug_en;

    /**
     * Create a new event instance.
     */
    public function __construct($cateProduct = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->cateProduct = $cateProduct;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $cateProduct?->slug_vn;
        $this->slug_en = $slug_en ?? $cateProduct?->slug_en;
    }
}
