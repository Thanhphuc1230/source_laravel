<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CateProjectRequest;
use App\Models\CateProject;
use App\Repositories\Interfaces\CateProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CateProjectController extends BaseController
{
    protected $module;
    protected $model;
    protected $nameItem;
    protected $imageFolder;
    protected $cateProjectRepository;

    public function __construct(CateProjectRepositoryInterface $cateProjectRepository, $imageFolder = 'cate_project')
    {
        $this->module = 'cate_project';
        $this->model = new CateProject;
        $this->nameItem = 'Danh mục dự án';
        $this->imageFolder = $imageFolder;
        $this->cateProjectRepository = $cateProjectRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'parent_id' => ($request->has('category') && $request->input('category') != 0) ? $request->input('category') : null,
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];

        $data['list'] = $this->cateProjectRepository->getFilteredCategories($filters);
        $data['nameItem'] = $this->nameItem;
        $data['category'] = $this->cateProjectRepository->getCategoriesWithChildren();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = $this->cateProjectRepository->getActiveParentCategories();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        $data['imageFolder'] = $this->imageFolder;

        return $this->view_admin('detail', $data);
    }

    public function store(CateProjectRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['status'] = 1;
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');

        $cateProject = $this->cateProjectRepository->createWithAutoSlug($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        if (class_exists(\App\Events\CateProject\CateProjectChanged::class)) {
            event(new \App\Events\CateProject\CateProjectChanged($cateProject, 'created'));
        }

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage = 1)
    {
        $category = $this->cateProjectRepository->findByUuid($uuid);

        if (! $category) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $category,
            'category' => $this->cateProjectRepository->getActiveParentCategories(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(CateProjectRequest $request, string $uuid)
    {
        $current = $this->cateProjectRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug_vn'] = empty($data['slug_vn']) ? $this->cateProjectRepository->generateUniqueSlug($data['name_vn'], $uuid, null, 'slug_vn') : Str::slug($data['slug_vn']);
        $data['slug_en'] = empty($data['slug_en']) && !empty($data['name_en']) ? $this->cateProjectRepository->generateUniqueSlug($data['name_en'], $uuid, null, 'slug_en') : (empty($data['slug_en']) ? null : Str::slug($data['slug_en']));

        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');

        $this->cateProjectRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        if (class_exists(\App\Events\CateProject\CateProjectChanged::class)) {
            event(new \App\Events\CateProject\CateProjectChanged($current, 'updated', $data['slug_vn'], $data['slug_en']));
        }

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $cateProject = $this->cateProjectRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        if (class_exists(\App\Events\CateProject\CateProjectChanged::class)) {
            event(new \App\Events\CateProject\CateProjectChanged($cateProject, 'status_updated'));
        }

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $cateProject = $this->cateProjectRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        if (class_exists(\App\Events\CateProject\CateProjectChanged::class)) {
            event(new \App\Events\CateProject\CateProjectChanged($cateProject, 'order_updated'));
        }

        return $result;
    }

    public function destroy(string $uuid)
    {
        $cateProject = $this->cateProjectRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        if (class_exists(\App\Events\CateProject\CateProjectChanged::class)) {
            event(new \App\Events\CateProject\CateProjectChanged($cateProject, 'deleted'));
        }

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        if (class_exists(\App\Events\CateProject\CateProjectChanged::class)) {
            event(new \App\Events\CateProject\CateProjectChanged(null, 'deleted'));
        }

        return $result;
    }
}
