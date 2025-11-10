<?php

namespace App\Events\Brand;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BrandChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $brand;

    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($brand = null, string $action = 'changed')
    {
        $this->brand = $brand;
        $this->action = $action;
    }
}