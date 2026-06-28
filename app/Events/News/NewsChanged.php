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

    public $action; // 'created', 'updated', 'deleted', 'status_updated'

    public $slug_vn;
    public $slug_en;

    /**
     * Create a new event instance.
     */
    public function __construct($news = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->news = $news;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $news?->slug_vn;
        $this->slug_en = $slug_en ?? $news?->slug_en;
    }
}
