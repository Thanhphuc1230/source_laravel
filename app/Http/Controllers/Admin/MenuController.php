<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Page;
use App\Models\CateNew;
use App\Models\CateProduct;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\MenuRequest;
class MenuController extends BaseController
{
    const TYPE_PAGE = 'page';
    const TYPE_CATE_NEW = 'cate_new';
    const TYPE_CATE_PRODUCT = 'cate_product';
    
    protected $module,$model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'menu')
    {
        $this->module = 'menu';
        $this->model = new Menu();
        $this->nameItem = 'Trang Menu';
        $this->imageFolder = $imageFolder;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function status($uuid, $status, $name)
    {
        return $this->statusManagementService->updateStatus($uuid, $status, $name,$this->model::class);
    }

    public function index()
    {
        //get page content
        $data['page_content'] = Page::where('status', 1)->orderBy('stt', 'asc')->get();

        //get category new
        $data['cate_new'] = CateNew::with([
            'children' => function ($query) {
                $query->select('id_cate_new', 'name_vn', 'parent_id', 'uuid');
            },
        ])
            ->where('status', 1)
            ->where('parent_id', 0)
            ->orderBy('stt', 'asc')
            ->get();
        //get category new
        $data['cate_product'] = CateProduct::with([
            'children' => function ($query) {
                $query->select('id_cate_product', 'name_vn', 'parent_id', 'uuid');
            },
        ])
            ->where('status', 1)
            ->where('parent_id', 0)
            ->orderBy('stt', 'asc')
            ->get();


        $data['menus'] = $this->getActiveMenusWithChildren();

        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    // Lấy menu active có children
    public function getActiveMenusWithChildren()
    {
        return $this->model->with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->orderBy('stt', 'asc')
            ->get();
    }

    public function store(MenuRequest $request)
    {

        $objectIds = $request->input('object_ids', []);
        if (empty($objectIds)) {
            $objectIds[0] = 0;
        }
        if ($objectIds) {
            foreach ($objectIds as $objectId) {
                $nameChild = $this->getNameVn($request->type, $objectId);
                if ($nameChild == NULL && $request->name_vn == NULL) {
                    return back()->with('error', 'Vui lòng chọn chính xác chủ đề và vị trí');
                }
                $data = [
                    'name_vn' => $nameChild['name_vn'] ?? $request->name_vn,
                    'name_en' => $nameChild['name_en'] ?? $request->name_en,
                    'slug' => $nameChild['slug'] ?? Str::slug($request->name_vn),
                    'object_id' => $objectId,
                    'parent_id' => $request->parent_id,
                    'stt' => 1,
                    'uuid' => Str::uuid(),
                    'created_at' => now(),
                    'link' => $request->link ?? null,
                    'type' => $request->type,
                ];

                $this->model::create($data);
            }

            toast('Thêm ' . $this->nameItem . ' thành công', 'success');
        } else {
            toast('Thêm ' . $this->nameItem . ' không thành công', 'error');
        }

        return $this->route_admin('index');
    }

    /**
     * Get Vietnamese name and slug based on type and ID
     *
     * @param string $type
     * @param int $id
     * @return array|null
     */
    private function getNameVn(string $type, int $id): ?array
    {
        switch ($type) {
            case self::TYPE_PAGE:
                $page = Page::find($id);
                return $page ? ['name_vn' => $page->name_vn, 'slug' => Str::slug($page->name_vn)] : null;
            case self::TYPE_CATE_NEW:
                $cateNew = CateNew::find($id);
                return $cateNew ? ['name_vn' => $cateNew->name_vn, 'slug' => Str::slug($cateNew->name_vn)] : null;
            case self::TYPE_CATE_PRODUCT:
                $cateProduct = CateProduct::find($id);
                return $cateProduct ? ['name_vn' => $cateProduct->name_vn, 'slug' => Str::slug($cateProduct->name_vn)] : null;
            default:
                return null;
        }
    }

    public function update(Request $request, string $uuid)
    {
        try {
            $data = array_merge(
                $request->except('_token'),
                ['updated_at' => now()]
            );
            
            $updated = $this->model::where('uuid', $uuid)->update($data);
            
            if (!$updated) {
                throw new \Exception('Không tìm thấy menu để cập nhật');
            }
            
            toast('Cập nhật ' . $this->nameItem . ' thành công', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }
        
        return $this->route_admin('index');
    }

    public function destroy(string $uuid)
    {
         return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }
}
