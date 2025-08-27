<?php

namespace App\Http\Controllers\Admin;

use App\Events\Page\PageChanged;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PageController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;

    public function __construct($imageFolder = 'page')
    {
        $this->module = 'page';
        $this->model = new Page;
        $this->nameItem = 'Trang nội dung';
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

        $data['list'] = $query->select('uuid', 'name_vn', 'slug', 'status', 'stt', 'created_at')->orderBy('created_at', 'desc')->paginate(10);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(PageRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        $data['slug'] = empty($data['slug']) ? $this->generateUniqueSlug($data['name_vn'], $this->model::class) : $data['slug'];
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null);

        // Handle image
        // Handle single image - Save new image
        $data['image'] = $this->saveImage($request);

        $page = $this->model::create($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        // Remove related cache
        PageChanged::dispatch($page, 'created', $data['slug']);

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
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(PageRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug'] = empty($data['slug']) ? $this->generateUniqueSlug($data['name_vn'], $this->model::class, $uuid) : $data['slug'];
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null, $current->created_at);

        // Handle image
        // Handle single image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->model::where('uuid', $uuid)->update($data);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        // Remove related cache
        PageChanged::dispatch($current, 'updated', $data['slug']);

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $name)
    {
        $page = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $name, $this->model::class);

        // Remove related cache
        PageChanged::dispatch($page, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $page = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        PageChanged::dispatch($page, 'order_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $page = $this->model::where('uuid', $uuid)->first();
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        // Remove related cache
        PageChanged::dispatch($page, 'deleted');

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        // Optimize: only select fields needed for events
        $pageItems = $this->model::whereIn('uuid', $uuids)
            ->select('uuid', 'slug', 'name_vn')
            ->get();

        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        PageChanged::dispatch(null, 'deleted');

        return $result;
    }
}
