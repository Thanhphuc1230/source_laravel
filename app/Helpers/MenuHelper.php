<?php

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

function getUrlMenu($item)
{
    if (isset($item->type) && $item->type != 'link' && (empty($item->object_id) || !is_numeric($item->object_id))) {
        return route('web.404');
    }

    switch ($item->type) {
        case 'page':
            $slug = Cache::remember("page_slug_{$item->object_id}", 360, function () use ($item) {
                return Page::where('id_page', $item->object_id)
                    ->where('status', 1)
                    ->value('slug');
            });

            if (empty($slug)) {
                return route('web.404');
            }

            return route('web.resolve', ['slug' => $slug]);

        case 'cate_new':
            $slug = Cache::remember("cate_new_slug_{$item->object_id}", 360, function () use ($item) {
                return CateNew::where('id_cate_new', $item->object_id)
                    ->where('status', 1)
                    ->value('slug');
            });

            if (empty($slug)) {
                return route('web.404');
            }

            return route('web.resolve', ['slug' => $slug]);

        case 'cate_product':
            $slug = Cache::remember("cate_product_slug_{$item->object_id}", 360, function () use ($item) {
                return CateProduct::where('id_cate_product', $item->object_id)
                    ->where('status', 1)
                    ->value('slug');
            });

            if (empty($slug)) {
                return route('web.404');
            }

            return route('web.resolve', ['slug' => $slug]);

        case 'link':
            if (empty($item->link) || $item->link === '#') {
                return '#';
            }
            return $item->link;

        default:
            return route('web.home');
    }
}

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
