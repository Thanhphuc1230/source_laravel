<?php

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getUrlMenu')) {
    function getUrlMenu($item)
    {
        if (isset($item->type) && $item->type != 'link' && (empty($item->object_id) || !is_numeric($item->object_id))) {
            return route('web.404');
        }

        if ($item->type === 'link') {
            if (empty($item->link) || $item->link === '#') {
                return '#';
            }
            return $item->link;
        }

        // Tạo cache key duy nhất
        $cacheKey = "menu_item_url_{$item->type}_{$item->object_id}";
        $cacheTtl = 7200; // 2 giờ

        // Sử dụng CacheService nếu có để hỗ trợ tag và tự động dọn dẹp
        if (class_exists(\App\Services\CacheService::class)) {
            return \App\Services\CacheService::remember(
                \App\Services\CacheService::TAGS['frontend'] ?? 'frontend',
                $cacheKey,
                $cacheTtl,
                function () use ($item) {
                    return getUrlMenuRaw($item);
                }
            );
        }

        return Cache::remember($cacheKey, $cacheTtl, function () use ($item) {
            return getUrlMenuRaw($item);
        });
    }
}

if (!function_exists('getUrlMenuRaw')) {
    function getUrlMenuRaw($item)
    {
        switch ($item->type) {
            case 'page':
                $page = Page::where('id_page', $item->object_id)
                    ->where('status', 1)
                    ->first();
                $slug = $page ? $page->slug : null;

                if (empty($slug)) {
                    return route('web.404');
                }

                return route('web.resolve', ['slug' => $slug]);

            case 'cate_new':
                $cateNew = CateNew::where('id_cate_new', $item->object_id)
                    ->where('status', 1)
                    ->first();
                $slug = $cateNew ? $cateNew->slug : null;

                if (empty($slug)) {
                    return route('web.404');
                }

                return route('web.resolve', ['slug' => $slug]);

            case 'cate_product':
                $cateProduct = CateProduct::where('id_cate_product', $item->object_id)
                    ->where('status', 1)
                    ->first();
                $slug = $cateProduct ? $cateProduct->slug : null;

                if (empty($slug)) {
                    return route('web.404');
                }

                return route('web.resolve', ['slug' => $slug]);

            case 'brand':
                $brand = \App\Models\Brand::where('id_brand', $item->object_id)
                    ->where('status', 1)
                    ->first();
                $slug = $brand ? ($brand->slug ?? \Illuminate\Support\Str::slug($brand->name_vn)) : null;

                if (empty($slug)) {
                    return route('web.404');
                }

                return route('web.resolve', ['slug' => $slug]);

            case 'cate_service':
                $cateService = \App\Models\CateService::where('id_cate_service', $item->object_id)
                    ->where('status', 1)
                    ->first();
                $slug = $cateService ? $cateService->slug : null;

                if (empty($slug)) {
                    return route('web.404');
                }

                return route('web.resolve', ['slug' => $slug]);

            case 'service':
                $service = \App\Models\Service::where('id_service', $item->object_id)
                    ->where('status', 1)
                    ->first();
                $slug = $service ? $service->slug : null;

                if (empty($slug)) {
                    return route('web.404');
                }

                return route('web.resolve', ['slug' => $slug]);

            case 'cate_project':
                $cateProject = \App\Models\CateProject::where('id_cate_project', $item->object_id)
                    ->where('status', 1)
                    ->first();
                $slug = $cateProject ? $cateProject->slug : null;

                if (empty($slug)) {
                    return route('web.404');
                }

                return route('web.resolve', ['slug' => $slug]);

            case 'project':
                $project = \App\Models\Project::where('id_project', $item->object_id)
                    ->where('status', 1)
                    ->first();
                $slug = $project ? $project->slug : null;

                if (empty($slug)) {
                    return route('web.404');
                }

                return route('web.resolve', ['slug' => $slug]);

            default:
                return route('web.home');
        }
    }
}

if (!function_exists('isActiveMenu')) {
    function isActiveMenu($item)
    {
        $currentUrl = request()->url();
        $menuUrl = getUrlMenu($item);

        if ($menuUrl == route('web.404')) {
            return '';
        }

        if ($menuUrl == route('web.home')) {
            return $currentUrl == $menuUrl ? 'active' : '';
        }

        if ($item->type === 'link' && $menuUrl === '#') {
            return '';
        }

        if ($menuUrl && $currentUrl == $menuUrl) {
            return 'active';
        }

        if (isset($item->children) && $item->children->isNotEmpty()) {
            foreach ($item->children as $child) {
                $childUrl = getUrlMenu($child);
                if ($childUrl && $childUrl != route('web.404') && $currentUrl == $childUrl) {
                    return 'active';
                }
            }
        }

        return '';
    }
}

if (!function_exists('searchInTree')) {
    /**
     * Helper function to search nested category trees for a given id
     * Tìm kiếm object trong cây phân cấp theo ID
     */
    function searchInTree($collection, $id, $idField = 'id_cate_new')
    {
        if (empty($collection)) {
            return null;
        }

        foreach ($collection as $item) {
            // Kiểm tra item hiện tại
            if (isset($item->{$idField}) && $item->{$idField} == $id) {
                return $item;
            }

            // Tìm kiếm đệ quy trong children
            if (!empty($item->children)) {
                $found = searchInTree($item->children, $id, $idField);
                if ($found) {
                    return $found;
                }
            }
        }

        return null;
    }
}

if (!function_exists('getMenuBelongName')) {
    /**
     * Lấy tên object dựa trên type và object_id
     * Trả về tên của page/category/brand mà menu đang trỏ tới
     */
    function getMenuBelongName($menuItem, $page_content = null, $cate_new = null, $cate_product = null, $brands = null, $cate_service = null, $cate_project = null)
    {
        if (!$menuItem || !isset($menuItem->type)) {
            return null;
        }

        switch ($menuItem->type) {
            case 'page':
                if (!$page_content) return null;
                $obj = searchInTree($page_content, $menuItem->object_id, 'id_page');
                return $obj->name_vn ?? null;

            case 'cate_new':
                if (!$cate_new) return null;
                $obj = searchInTree($cate_new, $menuItem->object_id, 'id_cate_new');
                return $obj->name_vn ?? null;

            case 'cate_product':
                if (!$cate_product) return null;
                $obj = searchInTree($cate_product, $menuItem->object_id, 'id_cate_product');
                return $obj->name_vn ?? null;

            case 'cate_service':
                if ($cate_service) {
                    $obj = searchInTree($cate_service, $menuItem->object_id, 'id_cate_service');
                    if ($obj) return $obj->name_vn;
                }
                $cs = \App\Models\CateService::find($menuItem->object_id);
                return $cs ? $cs->name_vn : null;

            case 'service':
                $s = \App\Models\Service::find($menuItem->object_id);
                return $s ? $s->name_vn : null;

            case 'cate_project':
                if ($cate_project) {
                    $obj = searchInTree($cate_project, $menuItem->object_id, 'id_cate_project');
                    if ($obj) return $obj->name_vn;
                }
                $cp = \App\Models\CateProject::find($menuItem->object_id);
                return $cp ? $cp->name_vn : null;

            case 'project':
                $p = \App\Models\Project::find($menuItem->object_id);
                return $p ? $p->name_vn : null;

            case 'brand':
                if ($brands) {
                    $obj = searchInTree($brands, $menuItem->object_id, 'id_brand');
                    if ($obj) return $obj->name_vn;
                }
                $brand = \App\Models\Brand::find($menuItem->object_id);
                return $brand ? $brand->name_vn : null;

            case 'link':
                return $menuItem->link ?? null;

            default:
                return null;
        }
    }
}

if (!function_exists('getMenuTypeLabel')) {
    /**
     * Lấy label hiển thị của loại menu
     */
    function getMenuTypeLabel($type)
    {
        $labels = [
            'page' => 'Trang nội dung',
            'cate_new' => 'Danh mục tin tức',
            'cate_product' => 'Danh mục sản phẩm',
            'cate_service' => 'Danh mục dịch vụ',
            'service' => 'Dịch vụ',
            'cate_project' => 'Danh mục dự án',
            'project' => 'Dự án',
            'brand' => 'Thương hiệu',
            'link' => 'Liên kết',
        ];

        return $labels[$type] ?? ucfirst($type);
    }
}

if (!function_exists('renderCategoryCheckbox')) {
    /**
     * Render checkbox đệ quy cho categories
     * @param object $item - Category item
     * @param string $idField - Tên field ID (id_cate_new, id_cate_product)
     * @param int $level - Level hiện tại (0 = parent)
     * @return string HTML
     */
    function renderCategoryCheckbox($item, $idField = 'id_cate_new', $level = 0)
    {
        $prefix = str_repeat('|--', $level);
        $html = '<div class="form-check mb-2">';
        $html .= '<input class="form-check-input" type="checkbox" name="object_ids[]" ';
        $html .= 'value="' . $item->{$idField} . '" id="formCheck' . $item->uuid . '">';
        $html .= '<label class="form-check-label" for="formCheck' . $item->uuid . '">';
        $html .= $prefix . $item->name_vn;
        $html .= '</label>';
        $html .= '</div>';

        // Render children recursively
        if (isset($item->children) && $item->children->isNotEmpty()) {
            foreach ($item->children as $child) {
                $html .= renderCategoryCheckbox($child, $idField, $level + 1);
            }
        }

        return $html;
    }
}

if (!function_exists('renderMenuOptions')) {
    /**
     * Render select options đệ quy cho menu
     * @param object $item - Menu item
     * @param int $level - Level hiện tại (0 = parent)
     * @return string HTML
     */
    function renderMenuOptions($item, $level = 0)
    {
        $prefix = str_repeat('|---', $level);
        $html = '<option value="' . $item->id_menu . '">';
        $html .= $prefix . $item->name_vn;
        $html .= '</option>';

        // Render children recursively
        if (isset($item->children) && $item->children->isNotEmpty()) {
            foreach ($item->children as $child) {
                $html .= renderMenuOptions($child, $level + 1);
            }
        }

        return $html;
    }
}