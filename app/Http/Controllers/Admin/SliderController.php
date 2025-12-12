<?php

namespace App\Http\Controllers\Admin;

use App\Events\Slider\SliderChanged;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use App\Repositories\Interfaces\SliderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class SliderController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $sliderRepository;

    public function __construct(SliderRepositoryInterface $sliderRepository, $imageFolder = 'slider')
    {
        $this->module = 'slider';
        $this->model = new Slider;
        $this->nameItem = 'Hình ảnh';
        $this->imageFolder = $imageFolder;
        $this->sliderRepository = $sliderRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'type' => $request->input('type', ''),
            'sort_field' => 'stt',
            'sort_direction' => 'asc'
        ];

        $data['list'] = $this->sliderRepository->getFilteredSliders($filters);
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

        // Handle image - Save new image
        $data['image'] = $this->saveImage($request);

        $slider = $this->sliderRepository->create($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        SliderChanged::dispatch($slider, 'created');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $slider = $this->sliderRepository->findByUuid($uuid);

        if (! $slider) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }

        $data = [
            'page' => $slider,
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(SliderRequest $request, string $uuid)
    {
        $current = $this->sliderRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');

        // Handle image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->sliderRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        SliderChanged::dispatch($current, 'updated');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $slider = $this->sliderRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        // Remove related cache
        SliderChanged::dispatch($slider, 'status_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $slider = $this->sliderRepository->findByUuid($uuid);

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
        
        // Lấy thông tin sliders trước khi xóa
        $sliders = $this->sliderRepository->findByUuids($uuids);
        
        SliderChanged::dispatch(null, 'deleted');

        // Gọi destroyAllByUUIDs để xóa cả hình ảnh (event sẽ được dispatch tự động)
        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $slider = $this->sliderRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        SliderChanged::dispatch($slider, 'order_updated');

        return $result;
    }
}
