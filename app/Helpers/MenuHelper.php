<?php

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Page;

function getUrlMenu($item)
{
    static $pageSlugs = [];
    static $cateNewSlugs = [];
    static $cateProductSlugs = [];

    switch ($item->type) {
        case 'page':
            if (! isset($pageSlugs[$item->object_id])) {
                $pageSlugs[$item->object_id] = Page::where('id_page', $item->object_id)->value('slug');
            }

            return route('web.resolve', ['slug' => $pageSlugs[$item->object_id]]);

        case 'cate_new':
            if (! isset($cateNewSlugs[$item->object_id])) {
                $cateNewSlugs[$item->object_id] = CateNew::where('id_cate_new', $item->object_id)->value('slug');
            }

            return route('web.resolve', ['slug' => $cateNewSlugs[$item->object_id]]);

        case 'cate_product':
            if (! isset($cateProductSlugs[$item->object_id])) {
                $cateProductSlugs[$item->object_id] = CateProduct::where('id_cate_product', $item->object_id)->value('slug');
            }

            return route('web.resolve', ['slug' => $cateProductSlugs[$item->object_id]]);

        case 'link':
            return $item->link;
        default:
            return '';
    }
}

function isActiveMenu($item)
{
    $currentUrl = request()->url();
    $menuUrl = getUrlMenu($item);

    // Xử lý đặc biệt cho trang chủ: chỉ active khi đúng chính xác route home
    if ($menuUrl == route('web.home')) {
        return $currentUrl == $menuUrl ? 'active' : '';
    }

    // Kiểm tra URL hiện tại có chứa URL của menu không (với các menu khác)
    if ($menuUrl && $currentUrl == $menuUrl) {
        return 'active';
    }

    // Kiểm tra menu con
    if ($item->children->isNotEmpty()) {
        foreach ($item->children as $child) {
            $childUrl = getUrlMenu($child);
            if ($childUrl && $currentUrl == $childUrl) {
                return 'active';
            }
        }
    }

    return '';
}
