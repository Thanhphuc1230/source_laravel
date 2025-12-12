<?php

namespace App\Http\Controllers\Admin;

use App\Events\Feature\FeatureChanged;
use App\Http\Requests\Admin\FeatureRequest;
use App\Models\Feature;
use App\Repositories\Interfaces\FeatureRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FeatureController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;

    protected $featureRepository;

    public function __construct(FeatureRepositoryInterface $featureRepository, $imageFolder = 'feature')
    {
        $this->module = 'feature';
        $this->model = new Feature;
        $this->nameItem = 'Tính năng';
        $this->imageFolder = $imageFolder;
        $this->featureRepository = $featureRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'sort_field' => 'stt',
            'sort_direction' => 'asc'
        ];

        $data['list'] = $this->featureRepository->getFilteredFeatures($filters);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(FeatureRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');

        // Handle image - Save new image
        $data['image'] = $this->saveImage($request);

        $feature = $this->featureRepository->create($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        FeatureChanged::dispatch($feature, 'created');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $feature = $this->featureRepository->findByUuid($uuid);

        if (! $feature) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }

        $data = [
            'page' => $feature,
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(FeatureRequest $request, string $uuid)
    {
        $current = $this->featureRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');

        // Handle image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->featureRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        FeatureChanged::dispatch($current, 'updated');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $feature = $this->featureRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        // Remove related cache
        FeatureChanged::dispatch($feature, 'status_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $feature = $this->featureRepository->findByUuid($uuid);

        if (! $feature) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }
        FeatureChanged::dispatch($feature, 'destroy');

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

        // Lấy thông tin features trước khi xóa
        $features = $this->featureRepository->findByUuids($uuids);

        FeatureChanged::dispatch(null, 'deleted');

        // Gọi destroyAllByUUIDs để xóa cả hình ảnh (event sẽ được dispatch tự động)
        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $feature = $this->featureRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        FeatureChanged::dispatch($feature, 'order_updated');

        return $result;
    }
}