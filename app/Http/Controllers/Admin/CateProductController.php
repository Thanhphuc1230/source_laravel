<?php

namespace App\Http\Controllers\Admin;

use App\Events\CateProduct\CateProductChanged;
use App\Http\Requests\Admin\CateProductRequest;
use App\Models\CateProduct;
use App\Repositories\Interfaces\CateProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CateProductController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $cateProductRepository;

    // Override default image config to allow SVG uploads
    protected $defaultImageConfig = [
        'convertToWebp' => false, // Disable WebP conversion for SVG compatibility
        'quality' => 80,
        'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml', 'image/svg', 'text/xml', 'application/xml', 'application/svg+xml'],
    ];

    public function __construct(CateProductRepositoryInterface $cateProductRepository, $imageFolder = 'cate_product')
    {
        $this->module = 'cate_product';
        $this->model = new CateProduct;
        $this->nameItem = 'Danh mục sản phẩm';
        $this->imageFolder = $imageFolder;
        $this->cateProductRepository = $cateProductRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $this->imageFolder);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'parent_id' => $request->has('category') ? $request->input('category') : null,
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];

        $data['list'] = $this->cateProductRepository->getFilteredCategories($filters);
        $data['nameItem'] = $this->nameItem;
        $data['category'] = $this->cateProductRepository->getCategoriesWithChildren();

        return $this->view_admin('list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['category'] = $this->cateProductRepository->getActiveParentCategories();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        $data['imageFolder'] = $this->imageFolder;

        return $this->view_admin('detail', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CateProductRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['status'] = 1;
        $data['image_vn'] = $this->saveImage($request, null, 'image_vn');
        $data['image_en'] = $this->saveImage($request, null, 'image_en');

        $cateProduct = $this->cateProductRepository->createWithAutoSlug($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');
        CateProductChanged::dispatch($cateProduct, 'created');

        // Xử lý redirect
        if ($request->has('return_back')) {
            return back();
        } elseif ($request->has('return_list')) {
            return $this->route_admin('index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid, $currentPage)
    {
        $category = $this->cateProductRepository->findByUuid($uuid);

        if ($category) {
            $data['page'] = $category;

            // get categories
            $data['category'] = $this->cateProductRepository->getActiveParentCategories();
            $data['action'] = 'edit';
            $data['nameItem'] = $this->nameItem;

            // save current page
            $data['currentPage'] = $currentPage;
            // folder image
            $data['imageFolder'] = $this->imageFolder;

            return $this->view_admin('detail', $data);
        } else {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CateProductRequest $request, string $uuid)
    {
        $current = $this->cateProductRepository->findByUuid($uuid);

        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug_vn'] = empty($data['slug_vn']) ? $this->cateProductRepository->generateUniqueSlug($data['name_vn'], $uuid, null, 'slug_vn') : \Illuminate\Support\Str::slug($data['slug_vn']);
        $data['slug_en'] = empty($data['slug_en']) && !empty($data['name_en']) ? $this->cateProductRepository->generateUniqueSlug($data['name_en'], $uuid, null, 'slug_en') : (empty($data['slug_en']) ? null : \Illuminate\Support\Str::slug($data['slug_en']));

        // Handle image - Update existing image
        $data['image_vn'] = $this->updateImage($request, $current, null, 'image_vn');
        $data['image_en'] = $this->updateImage($request, $current, null, 'image_en');

        $this->cateProductRepository->update($data, $uuid);

        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        // Remove related cache
        CateProductChanged::dispatch($current, 'updated', $data['slug_vn'], $data['slug_en']);

        $currentPage = $request->input('currentPage');

        return $this->route_admin('index', [], [], $currentPage);
    }

    /**
     * Update the numerical order of resources.
     */
    public function numericalOrder(Request $request, $uuid)
    {
        $cateProduct = $this->cateProductRepository->findByUuid($uuid);
        $result = $this->updateStt($request, $uuid);

        // Remove related cache
        CateProductChanged::dispatch($cateProduct, 'order_updated');

        return $result;
    }

    public function status($uuid, $status, $field)
    {
        $cateProduct = $this->cateProductRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        // Remove related cache
        CateProductChanged::dispatch($cateProduct, 'status_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $cateProduct = $this->cateProductRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        // Remove related cache
        CateProductChanged::dispatch($cateProduct, 'deleted');

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        // Optimize: only select fields needed for events
        $cateProductItems = $this->cateProductRepository->findByUuids($uuids, ['uuid', 'slug', 'name_vn', 'parent_id']);

        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        // Optimized event dispatch with minimal data
        CateProductChanged::dispatch(null, 'deleted');

        return $result;
    }
}
