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
    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($cateNew = null, string $action = 'changed')
    {
        $this->cateNew = $cateNew;
        $this->action = $action;
    }
}