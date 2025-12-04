<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ContactController extends BaseController
{
    protected $nameItem;
    protected $imageFolder;
    protected $contactRepository;
    protected $model;

    public function __construct(ContactRepositoryInterface $contactRepository, $imageFolder = 'contact')
    {
        $this->nameItem = 'Liên hệ';
        $this->imageFolder = $imageFolder;
        $this->contactRepository = $contactRepository;
        $this->model = 'App\\Models\\Contact';

        parent::__construct($imageFolder);
        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];
        $data['list'] = $this->contactRepository->getFilteredContacts($filters);
        $data['nameItem'] = $this->nameItem;
        return $this->view_admin('list', $data);
    }

    public function status($uuid, $status, $name)
    {
        return $this->updateStatus($uuid, $status, $name);
    }

    public function edit($uuid)
    {
        $contact = $this->contactRepository->findByUuid($uuid);
        if ($contact) {
            $data['page'] = $contact;
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
        return $this->dataRemovalService->destroyData($this->model, $uuid, $this->imageFolder);
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        return $this->dataRemovalService->destroyAllByUUIDs($this->model, $uuids, $this->imageFolder);
    }
}
