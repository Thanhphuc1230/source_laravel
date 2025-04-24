<?php

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Page;

function urlMenu($item)
{
    switch ($item->type) {
        case 'page':
            $slugPage = Page::where('id_page', $item->object_id)->value('slug');
            return route('web.page', ['slug_page' => $slugPage]);

        case 'cate_new':
            $slugCateNew = CateNew::where('id_cate_new', $item->object_id)->value('slug');
            return route('web.categoryNews', ['slug_cate_new' => $slugCateNew]);

        case 'cate_product':
            $slugCateProduct = CateProduct::where('id_cate_product', $item->object_id)->value('slug');
            return route('web.categoryProduct', ['slug_cate_product' => $slugCateProduct]);

        case 'link':
            return $item->link;
        default:
            return '';
    }
}

function isActiveMenu($item)
{
    $currentUrl = request()->url();
    $menuUrl = urlMenu($item);
    
    // Xử lý đặc biệt cho trang chủ
    if ($menuUrl == '/') {
        return $currentUrl == route('web.home') ? 'active' : '';
    }
    
    // Kiểm tra URL hiện tại có chứa URL của menu không
    if (strpos($currentUrl, $menuUrl) === 0) {
        return 'active';
    }
    
    // Kiểm tra menu con
    if ($item->children->isNotEmpty()) {
        foreach ($item->children as $child) {
            $childUrl = urlMenu($child);
            if (strpos($currentUrl, $childUrl) === 0) {
                return 'active';
            }
        }
    }
    
    return '';
}
