<?php

namespace App\Events\News;

use App\Models\News;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $news;
    public $action; // 'created', 'updated', 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct($news = null, string $action = 'changed')
    {
        $this->news = $news;
        $this->action = $action;
    }
}