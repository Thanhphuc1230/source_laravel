<?php

namespace App\Events\CateService;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CateServiceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cateService;
    public $action;
    public $slug_vn;
    public $slug_en;

    public function __construct($cateService = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->cateService = $cateService;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $cateService?->slug_vn;
        $this->slug_en = $slug_en ?? $cateService?->slug_en;
    }
}
