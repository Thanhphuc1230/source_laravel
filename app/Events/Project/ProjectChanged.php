<?php

namespace App\Events\Project;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $action;
    public $slug_vn;
    public $slug_en;

    public function __construct($project = null, string $action = 'changed', ?string $slug_vn = null, ?string $slug_en = null)
    {
        $this->project = $project;
        $this->action = $action;
        $this->slug_vn = $slug_vn ?? $project?->slug_vn;
        $this->slug_en = $slug_en ?? $project?->slug_en;
    }
}
