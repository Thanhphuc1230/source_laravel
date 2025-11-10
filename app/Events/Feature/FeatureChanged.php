<?php

namespace App\Events\Feature;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeatureChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $feature;

    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($feature = null, string $action = 'changed')
    {
        $this->feature = $feature;
        $this->action = $action;
    }
}