<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MailConfigRequest;
use App\Services\MailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MailConfigController extends BaseController
{
    protected $module;
    protected $nameItem;
    protected $mailConfigService;

    public function __construct(MailConfigService $mailConfigService)
    {
        $this->module = 'mail-config';
        $this->nameItem = 'Cấu hình mail';
        $this->mailConfigService = $mailConfigService;

        parent::__construct($this->module);
        View::share('nameClass', $this->module);
    }

    public function index()
    {
        $data['configs'] = $this->mailConfigService->getAllConfigs();
        return $this->view_admin('index', $data);
    }

    public function create()
    {
        return $this->view_admin('create');
    }

    public function store(MailConfigRequest $request)
    {
        $data = $request->validated();

        if ($this->mailConfigService->createConfig($data)) {
            toast('Thêm cấu hình mail thành công', 'success');
            return redirect()->route('admin.mail-config.index');
        }

        toast('Thêm cấu hình mail thất bại', 'error');
        return redirect()->back()->withInput();
    }

    public function edit($id)
    {
        $data['config'] = $this->mailConfigService->getConfigById($id);

        if (!$data['config']) {
            toast('Cấu hình mail không tồn tại', 'error');
            return redirect()->route('admin.mail-config.index');
        }

        return $this->view_admin('edit', $data);
    }

    public function update($id, MailConfigRequest $request)
    {
        $data = $request->validated();

        if ($this->mailConfigService->updateConfig($data, $id)) {
            toast('Cập nhật cấu hình mail thành công', 'success');
        } else {
            toast('Cập nhật cấu hình mail thất bại', 'error');
        }

        return redirect()->route('admin.mail-config.index');
    }

    public function setActive($id)
    {
        if ($this->mailConfigService->setActiveConfig($id)) {
            toast('Đặt cấu hình mail hoạt động thành công', 'success');
        } else {
            toast('Đặt cấu hình mail hoạt động thất bại', 'error');
        }

        return redirect()->route('admin.mail-config.index');
    }

    public function testConfig($id)
    {
        $config = $this->mailConfigService->getConfigById($id);

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'Cấu hình mail không tồn tại'
            ]);
        }

        $result = $this->mailConfigService->testConfig($config->toConfigArray());

        return response()->json($result);
    }

    public function destroy($id)
    {
        if ($this->mailConfigService->deleteConfig($id)) {
            toast('Xóa cấu hình mail thành công', 'success');
        } else {
            toast('Xóa cấu hình mail thất bại', 'error');
        }

        return redirect()->route('admin.mail-config.index');
    }
}
