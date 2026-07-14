<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SystemRequest;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class SystemController extends BaseController
{
    protected $module;
    protected $nameItem;
    protected $imageFolder;
    protected $systemRepository;

    public function __construct(SystemRepositoryInterface $systemRepository, $imageFolder = 'system')
    {
        $this->module = 'system';
        $this->nameItem = 'Hệ thống';
        $this->imageFolder = $imageFolder;
        $this->systemRepository = $systemRepository;

        parent::__construct($this->module, $imageFolder);
        View::share('nameClass', $imageFolder);
    }

    public function index()
    {
        $data['system'] = $this->systemRepository->all()->first();
        return $this->view_admin('index', $data);
    }

    public function update($id, SystemRequest $request)
    {
        $data = $request->except('_token');
        $data['created_at'] = now();
        $system = $this->systemRepository->find($id);

        // Handle logo - Update existing logo
        $data['logo'] = $this->updateImage($request, $system, 'logo', 'logo');

        // Handle favicon - Update existing favicon
        $data['favicon'] = $this->updateImage($request, $system, 'logo', 'favicon');

        Cache::forget('website_data');
        if ($system) {
            $this->systemRepository->update($data, $id);
            toast('Cập nhật hệ thống thành công', 'success');
        } else {
            toast('System not found', 'error');
        }

        return back();
    }

    public function clearCache()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            
            // Invalidate model-caching cache if library is present
            if (class_exists(\GeneaLabs\LaravelModelCaching\Helper::class)) {
                \Illuminate\Support\Facades\Artisan::call('modelCache:clear');
            }
            
            toast('Đã xóa toàn bộ bộ nhớ đệm (cache) hệ thống thành công!', 'success');
        } catch (\Exception $e) {
            toast('Có lỗi xảy ra khi xóa cache: ' . $e->getMessage(), 'error');
        }

        return back();
    }
}
