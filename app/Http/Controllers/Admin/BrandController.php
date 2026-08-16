<?php

namespace App\Http\Controllers\Admin;

use App\Events\Brand\BrandChanged;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use App\Traits\Admin\CrudOperationsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BrandController extends BaseController
{
    use CrudOperationsTrait;

    protected $module;
    protected $model;
    protected $nameItem;
    protected $imageFolder;
    protected $brandRepository;
    protected $repository; // For trait
    protected $eventClass = BrandChanged::class; // For trait

    public function __construct(BrandRepositoryInterface $brandRepository, $imageFolder = 'brand')
    {
        $this->module = 'brand';
        $this->model = new Brand;
        $this->nameItem = 'Thương hiệu';
        $this->imageFolder = $imageFolder;
        $this->brandRepository = $brandRepository;
        $this->repository = $brandRepository; // For trait

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

    public function store(BrandRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        
        // Handle multilingual images
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');

        $brand = $this->brandRepository->create($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');

        BrandChanged::dispatch($brand, 'created');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function update(BrandRequest $request, string $uuid)
    {
        $current = $this->brandRepository->findByUuid($uuid);
        
        if (!$current) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        
        // Handle multilingual images
        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');

        $this->brandRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        BrandChanged::dispatch($current, 'updated');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function edit($uuid, $currentPage)
    {
        return $this->performEdit($uuid, $currentPage);
    }

    public function status($uuid, $status, $field)
    {
        $brand = $this->brandRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);
        BrandChanged::dispatch($brand, 'status_updated');
        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $brand = $this->brandRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);
        BrandChanged::dispatch($brand, 'order_updated');
        return $result;
    }

    public function destroy(string $uuid)
    {
        $brand = $this->brandRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
        BrandChanged::dispatch($brand, 'deleted');
        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
        BrandChanged::dispatch(null, 'deleted');
        return $result;
    }
}
