<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\CateNewRequest;
use App\Events\CateNew\CateNewChanged;
use App\Events\Content\ContentChanged;

class CateNewController extends BaseController
{
    protected $module,$model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'cate_new')
    {
        $this->module = 'cate_new';
        $this->model = new CateNew();
        $this->nameItem = 'Danh mục tin tức';
        $this->imageFolder = $imageFolder;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $query = $this->model::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // Kiểm tra nếu đã chọn chủ đề
        if ($request->has('category') && $request->input('category') != 0) {
            $categoryId = $request->input('category');
            $query->where(function ($q) use ($categoryId) {
                $q->where('parent_id', $categoryId)
                  ->orWhere('id_cate_new', $categoryId);
            });
        }

        $data['list'] = $query->select('uuid', 'name_vn', 'slug', 'status','home', 'stt', 'created_at')->orderBy('created_at','desc')->paginate(10);
        $data['nameItem'] = $this->nameItem;

        $data['category'] = $this->model
            ::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = $this->model::where('status', 1)->where('parent_id', 0)->with('children.children')->orderBy('name_vn', 'asc')->get();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(CateNewRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        $data['slug'] = empty($data['slug']) ? $this->generateUniqueSlug($data['name_vn'], $this->model::class) : $data['slug'];
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null);
        $data['status'] = 1;

        // Handle image - Save new image
        $data['image'] = $this->saveImage($request);

        $cateNew = $this->model::create($data);
        toast('Thêm ' . $this->nameItem . ' thành công', 'success');

        // Remove related cache
        CateNewChanged::dispatch($cateNew, 'created', $data['slug']);

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $page = $this->model::where('uuid', $uuid);

        if (!$page->exists()) {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $page->first(),
            'category' => $this->model::where('status', 1)->where('parent_id', 0)->orderBy('name_vn', 'asc')->get(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(CateNewRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug'] = empty($data['slug']) ? $this->generateUniqueSlug($data['name_vn'], $this->model::class, $uuid) : $data['slug'];
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null, $current->created_at);

        // Handle image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->model::where('uuid', $uuid)->update($data);
        toast('Cập nhật ' . $this->nameItem . ' thành công', 'success');

        // Remove related cache
        CateNewChanged::dispatch($current, 'updated', $data['slug']);

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $name)
    {
        $cateNew = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $name, $this->model::class);

        // Remove related cache
        CateNewChanged::dispatch($cateNew, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $cateNew = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        CateNewChanged::dispatch($cateNew, 'order_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $cateNew = $this->model::where('uuid', $uuid)->first();
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        // Remove related cache
        CateNewChanged::dispatch($cateNew, 'deleted');

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        // Optimize: only select fields needed for events
        $cateNewItems = $this->model::whereIn('uuid', $uuids)
            ->select('uuid', 'slug', 'name_vn', 'parent_id')
            ->get();

        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        // Optimized event dispatch with minimal data
        CateNewChanged::dispatch(null, 'deleted');

        return $result;
    }
}
