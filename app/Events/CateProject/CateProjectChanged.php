<?php

namespace App\Events\CateProject;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CateProjectChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cateProject;
    public $action;
    public $slug_vn;
    public $slug_en;

    public function __construct($cateProject = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->cateProject = $cateProject;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $cateProject?->slug_vn;
        $this->slug_en = $slug_en ?? $cateProject?->slug_en;
    }
}
