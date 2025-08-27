<?php

namespace App\Events\Product;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;

    public $action; // 'created', 'updated', 'deleted', 'status_updated'

    public $slug; // Thêm slug để listener có thể xóa slug resolution cache

    /**
     * Create a new event instance.
     */
    public function __construct($product = null, string $action = 'changed', ?string $slug = null)
    {
        $this->product = $product;
        $this->action = $action;
        $this->slug = $slug ?? $product?->slug; // Auto-detect slug từ product
    }
}
