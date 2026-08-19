<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function categoryProject($id_cate_project)
    {
        $data = $this->projectService->getCategoryProjectData($id_cate_project);

        return view('frontend.modules.project.category', $data);
    }

    public function detailProject($id_project)
    {
        $data = $this->projectService->getDetailProjectData($id_project);

        return view('frontend.modules.project.detail', $data);
    }
}
