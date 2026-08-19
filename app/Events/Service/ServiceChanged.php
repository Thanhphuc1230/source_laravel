<?php

namespace App\Events\Service;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $service;
    public $action;
    public $slug_vn;
    public $slug_en;

    public function __construct($service = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->service = $service;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $service?->slug_vn;
        $this->slug_en = $slug_en ?? $service?->slug_en;
    }
}
