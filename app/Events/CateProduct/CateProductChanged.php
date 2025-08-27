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

    public $slug; // Thêm slug để listener có thể xóa slug resolution cache

    /**
     * Create a new event instance.
     */
    public function __construct($cateProduct = null, string $action = 'changed', ?string $slug = null)
    {
        $this->cateProduct = $cateProduct;
        $this->action = $action;
        $this->slug = $slug ?? $cateProduct?->slug; // Auto-detect slug từ cateProduct
    }
}
