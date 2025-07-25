<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class SystemController extends BaseController
{   
    protected $module,$model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'system')
    {
        $this->module = 'system';
        $this->model = new System();
        $this->nameItem = 'Hệ thống';
        $this->imageFolder = $imageFolder;

        parent::__construct($this->module, $imageFolder);

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

        // Handle logo
        $data['logo'] = $this->handleSingleImage($request, $system, 'logo', 'logo');

        // Handle favicon
        $data['favicon'] = $this->handleSingleImage($request, $system, 'logo', 'favicon');

        Cache::forget('website_data');
        if ($system) {
            $system->update($data);
            toast('Cập nhật hệ thống thành công', 'success');
        } else {
            toast('System not found', 'error');
        }
        return back();
    }
}
