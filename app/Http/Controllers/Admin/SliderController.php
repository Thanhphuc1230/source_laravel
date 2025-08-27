<?php

namespace App\Http\Controllers\Admin;

use App\Events\Slider\SliderChanged;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class SliderController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;

    public function __construct($imageFolder = 'slider')
    {
        $this->module = 'slider';
        $this->model = new Slider;
        $this->nameItem = 'Hình ảnh';
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

        $data['list'] = $query->orderBy('created_at', 'desc')->paginate(10);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(SliderRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null);

        // Handle image - Save new image
        $data['image'] = $this->saveImage($request);

        $slider = $this->model::create($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        SliderChanged::dispatch($slider, 'created');

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

    public function update(SliderRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null, $current->created_at);

        // Handle image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->model::where('uuid', $uuid)->update($data);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        SliderChanged::dispatch($current, 'updated');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $name)
    {
        $slider = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $name, $this->model::class);

        // Remove related cache
        SliderChanged::dispatch($slider, 'status_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $slider = $this->model::where('uuid', $uuid)->first();

        if (! $slider) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }
        SliderChanged::dispatch($slider, 'destroy');

        // Gọi destroyData để xóa cả hình ảnh (event sẽ được dispatch tự động)
        return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        if (empty($uuids)) {
            toast('Không có mục nào được chọn để xóa.', 'error');

            return redirect()->back();
        }
        SliderChanged::dispatch(null, 'deleted');

        // Gọi destroyAllByUUIDs để xóa cả hình ảnh (event sẽ được dispatch tự động)
        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $slider = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        SliderChanged::dispatch($slider, 'order_updated');

        return $result;
    }
}
