<?php

namespace App\Http\Controllers\Admin;

use App\Events\Brand\BrandChanged;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class BrandController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;

    protected $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository, $imageFolder = 'brand')
    {
        $this->module = 'brand';
        $this->model = new Brand;
        $this->nameItem = 'Đối tác';
        $this->imageFolder = $imageFolder;
        $this->brandRepository = $brandRepository;

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

        $data['list'] = $this->brandRepository->getFilteredBrands($filters);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(BrandRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');

        // Handle image - Save new image
        $data['image'] = $this->saveImage($request);

        $brand = $this->brandRepository->create($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        BrandChanged::dispatch($brand, 'created');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $brand = $this->brandRepository->findByUuid($uuid);

        if (! $brand) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }

        $data = [
            'page' => $brand,
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(BrandRequest $request, string $uuid)
    {
        $current = $this->brandRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');

        // Handle image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->brandRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        BrandChanged::dispatch($current, 'updated');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $brand = $this->brandRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        // Remove related cache
        BrandChanged::dispatch($brand, 'status_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $brand = $this->brandRepository->findByUuid($uuid);

        if (! $brand) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }
        BrandChanged::dispatch($brand, 'destroy');

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

        // Lấy thông tin brands trước khi xóa
        $brands = $this->brandRepository->findByUuids($uuids);

        BrandChanged::dispatch(null, 'deleted');

        // Gọi destroyAllByUUIDs để xóa cả hình ảnh (event sẽ được dispatch tự động)
        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $brand = $this->brandRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        BrandChanged::dispatch($brand, 'order_updated');

        return $result;
    }
}