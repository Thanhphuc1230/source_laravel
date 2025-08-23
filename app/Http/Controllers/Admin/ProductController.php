<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CateProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\ProductRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use App\Events\Product\ProductChanged;
use App\Events\Content\ContentChanged;

class ProductController extends BaseController
{
    protected $module,$model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'product')
    {
        $this->module = 'product';
        $this->model = new Product();
        $this->nameItem = 'sản phẩm';
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
            $query->where(function ($q) use ($categoryId) {
                $q->where('parent_id', $categoryId)
                  ->orWhere('id_category_product', $categoryId);
            });
        }

        $data['list'] = $query->select('uuid', 'name_vn', 'slug', 'status','home', 'stt', 'created_at','category_id','image')->orderBy('created_at','desc')->paginate(10);
        $data['nameItem'] = $this->nameItem;

        $data['category'] = CateProduct::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = CateProduct::where('status', 1)->where('parent_id', 0)->with('children.children')->orderBy('name_vn', 'asc')->get();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        $data['slug'] = empty($data['slug']) ? $this->generateUniqueSlug($data['name_vn'], $this->model::class) : $data['slug'];
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null);
        $data['status'] = 1;

        // Handle single image - Save new image
        $data['image'] = $this->saveImage($request);

        // Handle multiple images - Save new images
        $data['image_detail'] = $this->saveMultipleImages($request);

        $product = $this->model::create($data);
        toast('Thêm ' . $this->nameItem . ' thành công', 'success');

        // Remove related cache
        ProductChanged::dispatch($product, 'created', $data['slug']);

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $page = $this->model::where('uuid', $uuid);

        if (!$page->exists()) {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $page->first(),
            'category' => CateProduct::where('status', 1)->where('parent_id', 0)->with('children.children')->orderBy('name_vn', 'asc')->get(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(ProductRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug'] = empty($data['slug']) ? $this->generateUniqueSlug($data['name_vn'], $this->model::class, $uuid) : $data['slug'];
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null, $current->created_at);

        // Handle single image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        // Handle multiple images - Update existing images
        $data['image_detail'] = $this->updateMultipleImages($request, $current);

        $this->model::where('uuid', $uuid)->update($data);
        toast('Cập nhật ' . $this->nameItem . ' thành công', 'success');

        // Remove related cache
        ProductChanged::dispatch($current, 'updated', $data['slug']);

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $name)
    {
        $product = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $name, $this->model::class);

        // Remove related cache
        ProductChanged::dispatch($product, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $product = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        ProductChanged::dispatch($product, 'order_updated');

        return $result;
    }

    public function deleteImage($uuid, $index)
    {
        $product = $this->model::where('uuid', $uuid)->firstOrFail();
        $images = json_decode($product->image_detail, true);

        if (isset($images[$index])) {
            $imageToDelete = $images[$index];
            // Delete the file from storage
            $oldImagePath = public_path('images/' . $this->imageFolder . '/' . $imageToDelete);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath); // Xóa hình ảnh cũ
            }

            // Remove the image from the array
            unset($images[$index]);
            // Re-index the array and save
            $product->image_detail = json_encode(array_values($images));
            $product->save();

            return response()->json(['message' => 'Hình ảnh đã được xóa thành công.'], 200); // Updated response
        }

        return response()->json(['message' => 'Không tìm thấy hình ảnh để xóa.'], 404); // Updated response
    }

    public function destroy(string $uuid)
    {
        $product = $this->model::where('uuid', $uuid)->first();
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        // Remove related cache
        ProductChanged::dispatch($product, 'deleted');

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        // Get items before deletion for event dispatch (optimize: only get necessary fields)
        $productItems = $this->model::whereIn('uuid', $uuids)
            ->select('uuid', 'slug', 'name_vn', 'category_id') // Only fields needed for events
            ->get();

        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        // Optimized event dispatch - individual events but with minimal data
        ProductChanged::dispatch(null, 'deleted');

        return $result;
    }
}
