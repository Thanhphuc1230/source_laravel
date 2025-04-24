<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
class SystemController extends BaseController
{   
    protected $model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'system')
    {
        $this->model = new System();
        $this->nameItem = 'Hệ thống';
        $this->imageFolder = $imageFolder;

        parent::__construct($imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index()
    {
        $data['system'] = System::first();
        return $this->view_admin('index', $data);
    }

    public function update($id, Request $request)
    {
        $data = $request->except('_token');
        $data['created_at'] = new \DateTime();
        $system = System::find($id);

        //Logo
        $data['logo'] = $this->imageService->updateImage($request, $system,'logo', 'logo', [
            'convertToWebp' => true,
            'quality' => 80,
            'mimeTypes' => ['image/jpeg', 'image/png','image/jpg', 'image/gif']
        ]);
        //Favicon
        $data['favicon'] = $this->imageService->updateImage($request, $system, 'logo', 'favicon', [
            'convertToWebp' => true,
            'quality' => 80,
            'mimeTypes' => ['image/jpeg', 'image/png','image/jpg', 'image/gif']
        ]);
        if ($system) {
            $system->update($data);
            toast('Cập nhật hệ thống thành công', 'success');
        } else {
            toast('System not found', 'error');
        }
        return back();
    }
}
