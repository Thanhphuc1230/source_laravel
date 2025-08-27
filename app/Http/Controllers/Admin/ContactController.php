<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ContactController extends BaseController
{
    protected $model;

    protected $nameItem;

    protected $imageFolder;

    public function __construct($imageFolder = 'contact')
    {
        $this->model = new Contact;
        $this->nameItem = 'Liên hệ';
        $this->imageFolder = $imageFolder;

        parent::__construct($imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $query = $this->model::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        $data['list'] = $query->paginate(10);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function status($uuid, $status, $name)
    {
        return $this->updateStatus($uuid, $status, $name);
    }

    public function edit($uuid)
    {
        $page = $this->model::where('uuid', $uuid);

        if ($page->exists()) {
            $data['page'] = $page->first();
            $data['action'] = 'edit';
            $data['nameItem'] = $this->nameItem;

            return $this->view_admin('detail', $data);
        } else {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }
    }

    public function destroy(string $uuid)
    {
        return $this->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }
}
