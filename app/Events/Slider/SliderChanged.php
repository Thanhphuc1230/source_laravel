<?php

namespace App\Events\Slider;

use App\Models\Slider;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SliderChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $slider;
    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($slider = null, string $action = 'changed')
    {
        $this->slider = $slider;
        $this->action = $action;
    }
} 