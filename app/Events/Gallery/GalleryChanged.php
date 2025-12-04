<?php

namespace App\Events\Gallery;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GalleryChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gallery;

    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($gallery = null, string $action = 'changed')
    {
        $this->gallery = $gallery;
        $this->action = $action;
    }
}