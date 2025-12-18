<?php

namespace App\View\Composers;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Product;
use App\Models\Slider;
use App\Models\System;
use App\Services\CacheService;
use App\Services\CartService;
use App\Services\SiteSettingService;
use Illuminate\View\View;

class FrontendComposer
{
    protected $cartService;
    protected $siteSettingService;

    public function __construct(CartService $cartService, SiteSettingService $siteSettingService)
    {
        $this->cartService = $cartService;
        $this->siteSettingService = $siteSettingService;
    }

    public function compose(View $view)
    {
        // Website data
        $data['website'] = CacheService::remember(
            CacheService::TAGS['website'],
            'website_data',
            CacheService::getTtl('long'),
            fn() => System::first()
        );

        // Menu data
        $data['menu'] = CacheService::remember(
            CacheService::TAGS['menu'],
            'menu_header',
            CacheService::getTtl('long'),
            fn() => Menu::with('children')
                ->where('parent_id', 0)
                ->orderBy('stt', 'asc')
                ->get()
        );

        // Ads (sliders)
        $data['ads'] = CacheService::remember(
            CacheService::TAGS['sliders'],
            'ads',
            CacheService::getTtl('long'),
            fn() => Slider::where('status', 1)
                ->orderBy('stt', 'asc')
                ->get()
        );

        // Category product
        $data['cate_product'] = CacheService::remember(
            CacheService::TAGS['categories'],
            'cate_product',
            CacheService::getTtl('long'),
            fn() => CateProduct::where('status', 1)
                ->where('parent_id', 0)
                ->orderBy('stt', 'asc')
                ->get()
        );

        // Category product footer
        $data['category_product_footer'] = CacheService::remember(
            CacheService::TAGS['categories'],
            'category_product_footer',
            CacheService::getTtl('long'),
            fn() => CateProduct::where('status', 1)
                ->whereIn('parent_id', [0, 1])
                ->orderBy('stt', 'asc')
                ->get()
        );

        // Category news footer
        $data['category_news_footer'] = CacheService::remember(
            CacheService::TAGS['categories'],
            'category_news_footer',
            CacheService::getTtl('long'),
            fn() => CateNew::where('status', 1)
                ->where('parent_id', 0)
                ->orderBy('stt', 'asc')
                ->get()
        );

        // Footer pages
        $data['footer_pages'] = CacheService::remember(
            CacheService::TAGS['pages'],
            'footer_pages',
            CacheService::getTtl('long'),
            fn() => Page::where('status', 1)
                ->where('footer', 1)
                ->orderBy('stt', 'asc')
                ->get()
        );

        // Cart count
        $data['cart_count'] = $this->cartService->getTotalItems();

        // Products hot
        $data['products_hot'] = CacheService::remember(
            CacheService::TAGS['products'],
            'products_hot',
            CacheService::getTtl('long'),
            fn() => Product::where('status', 1)
                ->where('hot', 1)
                ->orderBy('stt', 'asc')
                ->limit(10)
                ->get()
        );

        $view->with($data);
    }
}