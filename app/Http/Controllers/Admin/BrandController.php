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
        $this->nameItem = 'Đối tác';
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

    /**
     * Store method uses trait
     */
    public function store(BrandRequest $request)
    {
        return $this->performStore($request);
    }

    /**
     * Edit method uses trait
     */
    public function edit($uuid, $currentPage)
    {
        return $this->performEdit($uuid, $currentPage);
    }

    /**
     * Update method uses trait
     */
    public function update(BrandRequest $request, string $uuid)
    {
        return $this->performUpdate($request, $uuid);
    }

    /**
     * Destroy method uses trait
     */
    public function destroy(string $uuid)
    {
        return $this->performDestroy($uuid);
    }

    /**
     * Destroy all method uses trait
     */
    public function destroyAll(Request $request)
    {
        return $this->performDestroyAll($request);
    }

    public function status($uuid, $status, $field)
    {
        $brand = $this->brandRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        // Dispatch event for cache invalidation
        BrandChanged::dispatch($brand, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $brand = $this->brandRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Dispatch event for cache invalidation
        BrandChanged::dispatch($brand, 'order_updated');

        return $result;
    }
}
