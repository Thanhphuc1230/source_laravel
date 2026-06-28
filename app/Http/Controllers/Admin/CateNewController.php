<?php

namespace App\Http\Controllers\Admin;

use App\Events\CateNew\CateNewChanged;
use App\Http\Requests\Admin\CateNewRequest;
use App\Models\CateNew;
use App\Repositories\Interfaces\CateNewRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CateNewController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $cateNewRepository;

    public function __construct(CateNewRepositoryInterface $cateNewRepository, $imageFolder = 'cate_new')
    {
        $this->module = 'cate_new';
        $this->model = new CateNew;
        $this->nameItem = 'Danh mục tin tức';
        $this->imageFolder = $imageFolder;
        $this->cateNewRepository = $cateNewRepository;

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

        $data['list'] = $this->cateNewRepository->getFilteredCategories($filters);
        $data['nameItem'] = $this->nameItem;
        $data['category'] = $this->cateNewRepository->getCategoriesWithChildren();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = $this->cateNewRepository->getActiveParentCategories();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        $data['imageFolder'] = $this->imageFolder;

        return $this->view_admin('detail', $data);
    }

    public function store(CateNewRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['status'] = 1;
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');

        $cateNew = $this->cateNewRepository->createWithAutoSlug($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');
        CateNewChanged::dispatch($cateNew, 'created');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $category = $this->cateNewRepository->findByUuid($uuid);

        if (! $category) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $category,
            'category' => $this->cateNewRepository->getActiveParentCategories(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(CateNewRequest $request, string $uuid)
    {
        $current = $this->cateNewRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug_vn'] = empty($data['slug_vn']) ? $this->cateNewRepository->generateUniqueSlug($data['name_vn'], $uuid, null, 'slug_vn') : \Illuminate\Support\Str::slug($data['slug_vn']);
        $data['slug_en'] = empty($data['slug_en']) && !empty($data['name_en']) ? $this->cateNewRepository->generateUniqueSlug($data['name_en'], $uuid, null, 'slug_en') : (empty($data['slug_en']) ? null : \Illuminate\Support\Str::slug($data['slug_en']));

        // Handle image - Update existing image
        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');

        $this->cateNewRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        // Remove related cache
        CateNewChanged::dispatch($current, 'updated', $data['slug_vn'], $data['slug_en']);

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $cateNew = $this->cateNewRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        // Remove related cache
        CateNewChanged::dispatch($cateNew, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $cateNew = $this->cateNewRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        CateNewChanged::dispatch($cateNew, 'order_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $cateNew = $this->cateNewRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        // Remove related cache
        CateNewChanged::dispatch($cateNew, 'deleted');

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        // Optimize: only select fields needed for events
        $cateNewItems = $this->cateNewRepository->findByUuids($uuids, ['uuid', 'slug', 'name_vn', 'parent_id']);

        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        // Optimized event dispatch with minimal data
        CateNewChanged::dispatch(null, 'deleted');

        return $result;
    }
}
