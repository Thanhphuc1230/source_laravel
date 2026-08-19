<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ServiceService;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function categoryService($id_cate_service)
    {
        $data = $this->serviceService->getCategoryServiceData($id_cate_service);

        return view('frontend.modules.service.category', $data);
    }

    public function detailService($id_service)
    {
        $data = $this->serviceService->getDetailServiceData($id_service);

        return view('frontend.modules.service.detail', $data);
    }
}
