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
    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($product = null, string $action = 'changed')
    {
        $this->product = $product;
        $this->action = $action;
    }
}