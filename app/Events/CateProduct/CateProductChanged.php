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
    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($cateProduct = null, string $action = 'changed')
    {
        $this->cateProduct = $cateProduct;
        $this->action = $action;
    }
}