<?php

namespace App\Http\Controllers\Admin;

use App\Events\Page\PageChanged;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PageController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository, $imageFolder = 'page')
    {
        $this->module = 'page';
        $this->model = new Page;
        $this->nameItem = 'Trang nội dung';
        $this->imageFolder = $imageFolder;
        $this->pageRepository = $pageRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'parent_id' => $request->input('parent_id', ''),
            'footer' => $request->input('footer', ''),
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];

        $data['list'] = $this->pageRepository->getFilteredPages($filters);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        $data['imageFolder'] = $this->imageFolder;

        return $this->view_admin('detail', $data);
    }

    public function store(PageRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');

        $page = $this->pageRepository->createWithAutoSlug($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');
        PageChanged::dispatch($page, 'created');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $page = $this->pageRepository->findByUuid($uuid);

        if (! $page) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }

        $data = [
            'page' => $page,
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(PageRequest $request, string $uuid)
    {
        $current = $this->pageRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug_vn'] = empty($data['slug_vn']) ? $this->pageRepository->generateUniqueSlug($data['name_vn'], $uuid, null, 'slug_vn') : \Illuminate\Support\Str::slug($data['slug_vn']);
        $data['slug_en'] = empty($data['slug_en']) && !empty($data['name_en']) ? $this->pageRepository->generateUniqueSlug($data['name_en'], $uuid, null, 'slug_en') : (empty($data['slug_en']) ? null : \Illuminate\Support\Str::slug($data['slug_en']));
        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');

        $this->pageRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');
        PageChanged::dispatch($current, 'updated', $data['slug_vn'], $data['slug_en']);

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $page = $this->pageRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);
        PageChanged::dispatch($page, 'status_updated');
        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $page = $this->pageRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);
        PageChanged::dispatch($page, 'order_updated');
        return $result;
    }

    public function destroy(string $uuid)
    {
        $page = $this->pageRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
        PageChanged::dispatch($page, 'deleted');
        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
        PageChanged::dispatch(null, 'deleted');
        return $result;
    }
}
