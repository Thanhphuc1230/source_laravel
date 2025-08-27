<?php

namespace App\Http\Controllers\Admin;

use App\Events\News\NewsChanged;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\CateNew;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class NewsController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;

    public function __construct($imageFolder = 'news')
    {
        $this->module = 'news';
        $this->model = new News;
        $this->nameItem = 'bài viết';
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
            $query->where('category_id', $categoryId);
        }

        $data['list'] = $query->select('uuid', 'name_vn', 'slug', 'status', 'home', 'stt', 'created_at', 'category_id', 'image')->orderBy('created_at', 'desc')->paginate(10);
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
        $data['slug'] = empty($data['slug']) ? $this->generateUniqueSlug($data['name_vn'], $this->model::class) : $data['slug'];
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null);
        $data['status'] = 1;

        // Handle image
        // Handle single image - Save new image
        $data['image'] = $this->saveImage($request);

        $news = $this->model::create($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        // Remove related cache
        NewsChanged::dispatch($news, 'created', $data['slug']);

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $page = $this->model::where('uuid', $uuid);

        if (! $page->exists()) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

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
        $data['slug'] = empty($data['slug']) ? $this->generateUniqueSlug($data['name_vn'], $this->model::class, $uuid) : $data['slug'];
        $data['updated_at'] = new \DateTime;
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null, $current->created_at);
        // Handle image
        // Handle single image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->model::where('uuid', $uuid)->update($data);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        // Remove related cache
        NewsChanged::dispatch($current, 'updated', $data['slug']);

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $name)
    {
        $news = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $name, $this->model::class);

        // Remove related cache
        NewsChanged::dispatch($news, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $news = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        NewsChanged::dispatch($news, 'order_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $news = $this->model::where('uuid', $uuid)->first();
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        // Remove related cache
        NewsChanged::dispatch($news, 'deleted');

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        // Chỉ cần xóa cache toàn bộ news
        NewsChanged::dispatch(null, 'deleted');

        return $result;
    }
}
