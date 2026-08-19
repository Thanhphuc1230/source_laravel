<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ServiceController extends BaseController
{
    protected $module;
    protected $model;
    protected $nameItem;
    protected $imageFolder;
    protected $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository, $imageFolder = 'service')
    {
        $this->module = 'service';
        $this->model = new Service;
        $this->nameItem = 'dịch vụ';
        $this->imageFolder = $imageFolder;
        $this->serviceRepository = $serviceRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];

        $data['list'] = $this->serviceRepository->getFilteredServices($filters);
        $data['nameItem'] = $this->nameItem;
        $data['category'] = $this->serviceRepository->getActiveCategories();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = $this->serviceRepository->getActiveCategories();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        $data['imageFolder'] = $this->imageFolder;

        return $this->view_admin('detail', $data);
    }

    public function store(ServiceRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');

        $service = $this->serviceRepository->createWithAutoSlug($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        if (class_exists(\App\Events\Service\ServiceChanged::class)) {
            event(new \App\Events\Service\ServiceChanged($service, 'created'));
        }

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage = 1)
    {
        $page = $this->serviceRepository->findByUuid($uuid);

        if (!$page) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $page,
            'category' => $this->serviceRepository->getActiveCategories(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(ServiceRequest $request, string $uuid)
    {
        $current = $this->serviceRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug_vn'] = empty($data['slug_vn']) ? $this->serviceRepository->generateUniqueSlug($data['name_vn'], $uuid, null, 'slug_vn') : Str::slug($data['slug_vn']);
        $data['slug_en'] = empty($data['slug_en']) && !empty($data['name_en']) ? $this->serviceRepository->generateUniqueSlug($data['name_en'], $uuid, null, 'slug_en') : (empty($data['slug_en']) ? null : Str::slug($data['slug_en']));

        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');

        $this->serviceRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        if (class_exists(\App\Events\Service\ServiceChanged::class)) {
            event(new \App\Events\Service\ServiceChanged($current, 'updated', $data['slug_vn'], $data['slug_en']));
        }

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $service = $this->serviceRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        if (class_exists(\App\Events\Service\ServiceChanged::class)) {
            event(new \App\Events\Service\ServiceChanged($service, 'status_updated'));
        }

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $service = $this->serviceRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        if (class_exists(\App\Events\Service\ServiceChanged::class)) {
            event(new \App\Events\Service\ServiceChanged($service, 'order_updated'));
        }

        return $result;
    }

    public function destroy(string $uuid)
    {
        $service = $this->serviceRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        if (class_exists(\App\Events\Service\ServiceChanged::class)) {
            event(new \App\Events\Service\ServiceChanged($service, 'deleted'));
        }

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        if (class_exists(\App\Events\Service\ServiceChanged::class)) {
            event(new \App\Events\Service\ServiceChanged(null, 'deleted'));
        }

        return $result;
    }
}
