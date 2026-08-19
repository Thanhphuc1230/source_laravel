<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ProjectController extends BaseController
{
    protected $module;
    protected $model;
    protected $nameItem;
    protected $imageFolder;
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository, $imageFolder = 'project')
    {
        $this->module = 'project';
        $this->model = new Project;
        $this->nameItem = 'dự án';
        $this->imageFolder = $imageFolder;
        $this->projectRepository = $projectRepository;

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

        $data['list'] = $this->projectRepository->getFilteredProjects($filters);
        $data['nameItem'] = $this->nameItem;
        $data['category'] = $this->projectRepository->getActiveCategories();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = $this->projectRepository->getActiveCategories();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        $data['imageFolder'] = $this->imageFolder;

        return $this->view_admin('detail', $data);
    }

    public function store(ProjectRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');

        $project = $this->projectRepository->createWithAutoSlug($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        if (class_exists(\App\Events\Project\ProjectChanged::class)) {
            event(new \App\Events\Project\ProjectChanged($project, 'created'));
        }

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage = 1)
    {
        $page = $this->projectRepository->findByUuid($uuid);

        if (!$page) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $page,
            'category' => $this->projectRepository->getActiveCategories(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(ProjectRequest $request, string $uuid)
    {
        $current = $this->projectRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug_vn'] = empty($data['slug_vn']) ? $this->projectRepository->generateUniqueSlug($data['name_vn'], $uuid, null, 'slug_vn') : Str::slug($data['slug_vn']);
        $data['slug_en'] = empty($data['slug_en']) && !empty($data['name_en']) ? $this->projectRepository->generateUniqueSlug($data['name_en'], $uuid, null, 'slug_en') : (empty($data['slug_en']) ? null : Str::slug($data['slug_en']));

        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');

        $this->projectRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        if (class_exists(\App\Events\Project\ProjectChanged::class)) {
            event(new \App\Events\Project\ProjectChanged($current, 'updated', $data['slug_vn'], $data['slug_en']));
        }

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $project = $this->projectRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        if (class_exists(\App\Events\Project\ProjectChanged::class)) {
            event(new \App\Events\Project\ProjectChanged($project, 'status_updated'));
        }

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $project = $this->projectRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        if (class_exists(\App\Events\Project\ProjectChanged::class)) {
            event(new \App\Events\Project\ProjectChanged($project, 'order_updated'));
        }

        return $result;
    }

    public function destroy(string $uuid)
    {
        $project = $this->projectRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        if (class_exists(\App\Events\Project\ProjectChanged::class)) {
            event(new \App\Events\Project\ProjectChanged($project, 'deleted'));
        }

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        if (class_exists(\App\Events\Project\ProjectChanged::class)) {
            event(new \App\Events\Project\ProjectChanged(null, 'deleted'));
        }

        return $result;
    }
}
