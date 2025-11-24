<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MailTemplateRequest;
use App\Services\MailTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MailTemplateController extends BaseController
{
    protected $module;
    protected $nameItem;
    protected $mailTemplateService;

    public function __construct(MailTemplateService $mailTemplateService)
    {
        $this->module = 'mail-template';
        $this->nameItem = 'Template mail';
        $this->mailTemplateService = $mailTemplateService;

        parent::__construct($this->module);
        View::share('nameClass', $this->module);
    }

    public function index()
    {
        $data['templates'] = $this->mailTemplateService->paginate(15);
        $data['nameItem'] = $this->nameItem;
        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        $data['types'] = [
            'order' => 'Đơn hàng',
            'contact' => 'Liên hệ'
        ];
        return $this->view_admin('detail', $data);
    }

    public function store(MailTemplateRequest $request)
    {
        $data = $request->validated();
        $data['variables'] = $request->input('variables', []);

        if ($this->mailTemplateService->create($data)) {
            toast('Thêm template mail thành công', 'success');
            return redirect()->route('admin.mail-template.index');
        }

        toast('Thêm template mail thất bại', 'error');
        return redirect()->back()->withInput();
    }

    public function edit($id)
    {
        $data['template'] = $this->mailTemplateService->getById($id);
        $data['action'] = 'edit';
        $data['nameItem'] = $this->nameItem;
        $data['types'] = [
            'order' => 'Đơn hàng',
            'contact' => 'Liên hệ'
        ];

        if (!$data['template']) {
            toast('Template mail không tồn tại', 'error');
            return redirect()->route('admin.mail-template.index');
        }

        return $this->view_admin('detail', $data);
    }

    public function update($id, MailTemplateRequest $request)
    {
        $data = $request->validated();
        $data['variables'] = $request->input('variables', []);

        if ($this->mailTemplateService->update($id, $data)) {
            toast('Cập nhật template mail thành công', 'success');
        } else {
            toast('Cập nhật template mail thất bại', 'error');
        }

        return redirect()->route('admin.mail-template.index');
    }

    public function setActive($id)
    {
        $template = $this->mailTemplateService->getById($id);
        if (!$template) {
            toast('Template mail không tồn tại', 'error');
            return redirect()->route('admin.mail-template.index');
        }

        $data = ['is_active' => !$template->is_active];

        if ($this->mailTemplateService->update($id, $data)) {
            $status = $data['is_active'] ? 'kích hoạt' : 'vô hiệu hóa';
            toast("{$status} template mail thành công", 'success');
        } else {
            toast('Cập nhật trạng thái template mail thất bại', 'error');
        }

        return redirect()->route('admin.mail-template.index');
    }

    public function destroy($id)
    {
        if ($this->mailTemplateService->delete($id)) {
            toast('Xóa template mail thành công', 'success');
        } else {
            toast('Xóa template mail thất bại', 'error');
        }

        return redirect()->route('admin.mail-template.index');
    }

    public function destroyAll(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            toast('Không có mục nào được chọn', 'warning');
            return redirect()->back();
        }

        $deleted = 0;
        foreach ($ids as $id) {
            if ($this->mailTemplateService->delete($id)) {
                $deleted++;
            }
        }

        if ($deleted > 0) {
            toast("Đã xóa {$deleted} template mail thành công", 'success');
        } else {
            toast('Xóa template mail thất bại', 'error');
        }

        return redirect()->route('admin.mail-template.index');
    }
}