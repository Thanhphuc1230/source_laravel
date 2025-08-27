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

    public $slug; // Thêm slug để listener có thể xóa slug resolution cache

    /**
     * Create a new event instance.
     */
    public function __construct($news = null, string $action = 'changed', ?string $slug = null)
    {
        $this->news = $news;
        $this->action = $action;
        $this->slug = $slug ?? $news?->slug; // Auto-detect slug từ news
    }
}
