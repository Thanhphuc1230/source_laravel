<?php

namespace App\Http\Controllers\Admin;

use App\Events\Menu\MenuChanged;
use App\Http\Requests\Admin\MenuRequest;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\CateNewRepositoryInterface;
use App\Repositories\Interfaces\CateProductRepositoryInterface;
use App\Models\Menu; // Importing Menu model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MenuController extends BaseController
{
    const TYPE_PAGE = 'page';

    const TYPE_CATE_NEW = 'cate_new';

    const TYPE_CATE_PRODUCT = 'cate_product';

    protected $module;

    protected $menuRepository;
    protected $pageRepository;
    protected $cateNewRepository;
    protected $cateProductRepository;

    protected $nameItem;

    protected $imageFolder;

    public function __construct(
        MenuRepositoryInterface $menuRepository,
        PageRepositoryInterface $pageRepository,
        CateNewRepositoryInterface $cateNewRepository,
        CateProductRepositoryInterface $cateProductRepository,
        $imageFolder = 'menu'
    ) {
        $this->module = 'menu';
        $this->menuRepository = $menuRepository;
        $this->pageRepository = $pageRepository;
        $this->cateNewRepository = $cateNewRepository;
        $this->cateProductRepository = $cateProductRepository;
        $this->nameItem = 'Trang Menu';
        $this->imageFolder = $imageFolder;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function status($uuid, $status, $field)
    {
        $menu = $this->menuRepository->findByUUID($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, get_class($menu));

        // Remove related cache
        MenuChanged::dispatch($menu, 'status_updated');

        return $result;
    }

    public function index()
    {
        // get page content
        $data['page_content'] = $this->pageRepository->getActivePages();

        // get category new
        $data['cate_new'] = $this->cateNewRepository->getCategoriesWithChildren();
        $data['cate_product'] = $this->cateProductRepository->getCategoriesWithChildren();

        $data['menus'] = $this->menuRepository->getMenuTree();

        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    // Lấy menu active có children
    // Deprecated: use $this->menuRepository->getMenuTree() instead

    public function store(MenuRequest $request)
    {
        $objectIds = $request->input('object_ids', []);
        if (empty($objectIds)) {
            $objectIds[0] = 0;
        }
        if ($objectIds) {
            foreach ($objectIds as $objectId) {
                $nameChild = $this->getNameVn($request->type, $objectId);
                if ($nameChild == null && $request->name_vn == null) {
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

                $menu = $this->menuRepository->create($data);

                // Dispatch event sau khi tạo menu
                MenuChanged::dispatch($menu, 'created');
            }

            toast('Thêm '.$this->nameItem.' thành công', 'success');
        } else {
            toast('Thêm '.$this->nameItem.' không thành công', 'error');
        }

        return $this->route_admin('index');
    }

    /**
     * Get Vietnamese name and slug based on type and ID
     */
    private function getNameVn(string $type, int $id): ?array
    {
        switch ($type) {
            case self::TYPE_PAGE:
                $page = $this->pageRepository->find($id);
                return $page ? ['name_vn' => $page->name_vn, 'slug' => $this->pageRepository->generateUniqueSlug($page->name_vn, $page->uuid)] : null;
            case self::TYPE_CATE_NEW:
                $cateNew = $this->cateNewRepository->find($id);
                return $cateNew ? ['name_vn' => $cateNew->name_vn, 'slug' => $this->cateNewRepository->generateUniqueSlug($cateNew->name_vn, $cateNew->uuid)] : null;
            case self::TYPE_CATE_PRODUCT:
                $cateProduct = $this->cateProductRepository->find($id);
                return $cateProduct ? ['name_vn' => $cateProduct->name_vn, 'slug' => $this->cateProductRepository->generateUniqueSlug($cateProduct->name_vn, $cateProduct->uuid)] : null;
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

            $menu = $this->menuRepository->findByUUID($uuid);

            if (! $menu) {
                throw new \Exception('Không tìm thấy menu để cập nhật');
            }

            $updated = $menu->update($data);

            if ($updated) {
                // Dispatch event sau khi cập nhật menu
                MenuChanged::dispatch($menu, 'updated');
            }

            toast('Cập nhật '.$this->nameItem.' thành công', 'success');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
        }

        return $this->route_admin('index');
    }

    public function destroy(string $uuid)
    {
        try {
            // Tìm menu theo UUID
            $menu = $this->menuRepository->findByUuid($uuid);

            if (!$menu) {
                toast('Không tìm thấy menu để xóa.', 'error');
                return back();
            }

            // Lấy tất cả menu con (children) của menu này
            $children = $this->menuRepository->getModelInstance()->where('parent_id', $menu->id_menu)->get();

            // Xóa menu con trước
            foreach ($children as $child) {
                // Xóa ảnh của menu con
                $this->deleteMenuImages($child, $this->imageFolder);
                // Dispatch event cho menu con
                MenuChanged::dispatch($child, 'deleted');
                // Xóa menu con
                $child->delete();
            }

            // Xóa ảnh của menu cha
            $this->deleteMenuImages($menu, $this->imageFolder);

            // Dispatch event cho menu cha
            MenuChanged::dispatch($menu, 'deleted');

            // Xóa menu cha
            $menu->delete();

            toast('Xóa menu thành công.', 'success');

        } catch (\Exception $e) {
            Log::error('Error deleting menu: ' . $e->getMessage());
            toast('Có lỗi xảy ra khi xóa menu.', 'error');
        }

        return back();
    }

    /**
     * Xóa tất cả ảnh liên quan đến menu
     */
    private function deleteMenuImages($menu, $imageFolder)
    {
        $imageFields = ['image'];

        foreach ($imageFields as $field) {
            if (isset($menu->$field) && $menu->$field) {
                $imagePath = public_path("images/{$imageFolder}/{$menu->$field}");
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
    }

    public function destroyAll(Request $request)
    {
        return $this->dataRemovalService->destroyAllByUUIDs(Menu::class, $request->input('uuids', []), $this->imageFolder);
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $menu = $this->menuRepository->findByUUID($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, get_class($menu));

        // Remove related cache
        MenuChanged::dispatch($menu, 'order_updated');

        return $result;
    }
}
