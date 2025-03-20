<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CateProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\CateProductRequest;
use App\Services\ImageService;
use App\Services\DataRemovalService;

class CateProductController extends BaseController
{   
    protected $imageService;
    protected $dataRemovalService;

    public function __construct(ImageService $imageService, DataRemovalService $dataRemovalService)
    {
        $this->model = new CateProduct();
        $this->nameItem = 'Chủ đề sản phẩm';
        $this->imageFolder = 'cate_product';

        parent::__construct($this->imageFolder);

        $this->imageService = $imageService;
        $this->dataRemovalService = $dataRemovalService;

        View::share('nameClass', $this->imageFolder);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->model::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // check if choose category
        if ($request->has('category') && $request->input('category') != 0) {
            $categoryId = $request->input('category');
            $query->where(function ($q) use ($categoryId) {
                $q->where('parent_id', $categoryId)->orWhere('id_cate_product', $categoryId);
            });
        }

        $data['list'] = $query->paginate(10);
        $data['nameItem'] = $this->nameItem;
        // category product
        $data['category'] = $this->model::with('children')->where('status', 1)->where('parent_id', 0)->get();

        return $this->view_admin('list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['category'] = $this->model::where('status', 1)->where('parent_id', 0)->with('children.children')->orderBy('name_vn', 'asc')->get(); //Lấy chủ đề cha
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;
        
        return $this->view_admin('detail', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CateProductRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        // Tạo slug từ name_vn nếu không có
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name_vn']);
        }
        $data['created_at'] = new \DateTime();
        $data['status'] = 1;
        // handle image
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->imageService->saveImage($request, $this->imageFolder, 'avatar');
        }

        $this->model::create($data);

        toast('Thêm ' . $this->nameItem . ' thành công', 'success');

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
    public function edit($uuid,$currentPage)
    {
        $page = $this->model::where('uuid', $uuid);

        if ($page->exists()) {
            $data['page'] = $page->first();

            // get category
            $data['category'] = $this->model
                ::where('status', 1)
                ->where('parent_id', 0)
                ->orderBy('name_vn', 'asc')
                ->get();
            $data['action'] = 'edit';
            $data['nameItem'] = $this->nameItem;

            // save current page
            $data['currentPage'] = $currentPage ;
            // folder image
            $data['imageFolder'] = $this->imageFolder;
            return $this->view_admin('detail', $data);
        } else {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CateProductRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();

        $data = $request->except('_token','return_back','return_list','currentPage');
        $data['updated_at'] = new \DateTime();

        $data['avatar'] = $this->imageService->updateImage($request, $current, $this->imageFolder, 'avatar');

        $this->model::where('uuid', $uuid)->update($data);

        toast('Cập nhật ' . $this->nameItem . ' thành công', 'success');

        $currentPage = $request->input('currentPage');
        return $this->route_admin('index', [], [], $currentPage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        
        // Sử dụng service với model object
        return $this->dataRemovalService->destroyAllByUUIDs(
            $this->model, 
            $uuids, 
            $this->imageFolder
        );
    }

    /**
     * Update the numerical order of resources.
     */
    public function numericalOrder(Request $request, $uuid)
    {
        return $this->updateStt($request, $uuid);
    }
}
