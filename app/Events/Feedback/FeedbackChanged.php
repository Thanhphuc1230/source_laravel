<?php

namespace App\Events\FeedBack;

use App\Models\FeedBack;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeedBackChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $feedback;

    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct(?FeedBack $feedback = null, string $action = 'changed')
    {
        $this->feedback = $feedback;
        $this->action = $action;
    }
}
