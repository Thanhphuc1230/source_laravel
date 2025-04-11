<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\CateNew;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\NewsRequest;
use Illuminate\Support\Facades\View;

class NewsController extends BaseController
{
    protected $model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'news')
    {
        $this->model = new News();
        $this->nameItem = 'bài viết';
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

        $data['list'] = $query->paginate(10);
        $data['nameItem'] = $this->nameItem;

        $data['category'] = CateNew::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = CateNew::where('status', 1)->where('parent_id', 0)->with('children.children')->orderBy('name_vn', 'asc')->get();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(NewsRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        $data['slug'] = empty($data['slug']) ? Str::slug($data['name_vn']) : $data['slug'];
        $data['created_at'] = new \DateTime();
        $data['status'] = 1;

        // handle image
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->saveImage($request, $this->imageFolder, 'image');
        }

        $this->model::create($data);
        toast('Thêm ' . $this->nameItem . ' thành công', 'success');

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
            'category' => CateNew::where('status', 1)->where('parent_id', 0)->with('children.children')->orderBy('name_vn', 'asc')->get(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(NewsRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['updated_at'] = new \DateTime();
        // Handle image
        $data['image'] = $this->imageService->updateImage($request, $current, $this->imageFolder, 'image');

        $this->model::where('uuid', $uuid)->update($data);
        toast('Cập nhật ' . $this->nameItem . ' thành công', 'success');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function destroy(string $uuid)
    {
        return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids');
  
        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function status($uuid, $status, $name)
    {
        return $this->statusManagementService->updateStatus($uuid, $status, $name,$this->model::class);
    }

    public function numericalOrder(Request $request, $uuid)
    {
        return $this->statusManagementService->updateStt($request, $uuid,$this->model::class);
    }
}
