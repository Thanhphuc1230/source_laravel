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

    public $action; // 'created', 'updated', 'deleted', 'status_updated'

    public $slug; // Thêm slug để listener có thể xóa slug resolution cache

    /**
     * Create a new event instance.
     */
    public function __construct($cateNew = null, string $action = 'changed', ?string $slug = null)
    {
        $this->cateNew = $cateNew;
        $this->action = $action;
        $this->slug = $slug ?? $cateNew?->slug; // Auto-detect slug từ cateNew
    }
}
