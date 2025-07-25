<?php

namespace App\Events\Feedback;

use App\Models\FeedBack;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeedbackChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $feedback;
    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct(FeedBack $feedback = null, string $action = 'changed')
    {
        $this->feedback = $feedback;
        $this->action = $action;
    }
} 