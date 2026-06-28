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

    public $slug_vn;
    public $slug_en;

    /**
     * Create a new event instance.
     */
    public function __construct($product = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->product = $product;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $product?->slug_vn;
        $this->slug_en = $slug_en ?? $product?->slug_en;
    }
}
