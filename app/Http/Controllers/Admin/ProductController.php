<?php

namespace App\Http\Controllers\Admin;

use App\Events\Product\ProductChanged;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository, $imageFolder = 'product')
    {
        $this->module = 'product';
        $this->model = new Product;
        $this->nameItem = 'sản phẩm';
        $this->imageFolder = $imageFolder;
        $this->productRepository = $productRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];

        $data['list'] = $this->productRepository->getFilteredProducts($filters);
        $data['nameItem'] = $this->nameItem;
        $data['category'] = $this->productRepository->getActiveCategories();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = $this->productRepository->getActiveCategories();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        $data['imageFolder'] = $this->imageFolder;

        return $this->view_admin('detail', $data);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');
        $data['image_detail'] = $this->saveMultipleImages($request);

        $product = $this->productRepository->createWithAutoSlug($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');
        ProductChanged::dispatch($product, 'created');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $page = $this->productRepository->findByUuid($uuid);
        
        if (!$page) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $page,
            'category' => $this->productRepository->getActiveCategories(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(ProductRequest $request, string $uuid)
    {
        $current = $this->productRepository->findByUuid($uuid);
        $data = $this->cleanRequestData($request);
        $data['slug_vn'] = empty($data['slug_vn']) ? $this->productRepository->generateUniqueSlug($data['name_vn'], $uuid, null, 'slug_vn') : \Illuminate\Support\Str::slug($data['slug_vn']);
        $data['slug_en'] = empty($data['slug_en']) && !empty($data['name_en']) ? $this->productRepository->generateUniqueSlug($data['name_en'], $uuid, null, 'slug_en') : (empty($data['slug_en']) ? null : \Illuminate\Support\Str::slug($data['slug_en']));
        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');
        $data['image_detail'] = $this->updateMultipleImages($request, $current);

        $this->productRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');
        ProductChanged::dispatch($current, 'updated', $data['slug_vn'], $data['slug_en']);

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $product = $this->productRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);
        ProductChanged::dispatch($product, 'status_updated');
        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $product = $this->productRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);
        ProductChanged::dispatch($product, 'order_updated');
        return $result;
    }

    public function destroy(string $uuid)
    {
        $product = $this->productRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
        ProductChanged::dispatch($product, 'deleted');
        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
        ProductChanged::dispatch(null, 'deleted');
        return $result;
    }
}
