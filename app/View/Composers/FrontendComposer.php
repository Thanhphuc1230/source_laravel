<?php

namespace App\View\Composers;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Product;
use App\Models\Slider;
use App\Models\System;
use App\Services\CartService;
use App\Services\SiteSettingService;
use Illuminate\Support\Facades\Cache;
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
        $cacheTtl = 120; // 2 giờ

        $data = [
            // 1. Dữ liệu hệ thống (Tag: system)
            'web' => \App\Services\CacheService::remember('system', 'frontend_web_data', $cacheTtl, function() {
                return System::first();
            }),

            // 2. Dữ liệu Menu (Tag: menu)
            'menu' => \App\Services\CacheService::remember('menu', 'frontend_menu_data', $cacheTtl, function() {
                return Menu::with('children')->where('parent_id', 0)->orderBy('stt', 'asc')->get();
            }),

            // 3. Slider/Ads (Tag: sliders)
            'ads' => \App\Services\CacheService::remember('sliders', 'frontend_sliders_data', $cacheTtl, function() {
                return Slider::where('status', 1)->orderBy('stt', 'asc')->get();
            }),

            // 4. Danh mục sản phẩm (Tag: categories)
            'cate_product' => \App\Services\CacheService::remember('categories', 'frontend_cate_product_data', $cacheTtl, function() {
                return CateProduct::where('status', 1)->where('parent_id', 0)->orderBy('stt', 'asc')->get();
            }),
            'category_product_footer' => \App\Services\CacheService::remember('categories', 'frontend_cate_product_footer_data', $cacheTtl, function() {
                return CateProduct::where('status', 1)->whereIn('parent_id', [0, 1])->orderBy('stt', 'asc')->get();
            }),

            // 5. Danh mục tin tức (Tag: categories)
            'category_news_footer' => \App\Services\CacheService::remember('categories', 'frontend_cate_news_footer_data', $cacheTtl, function() {
                return CateNew::where('status', 1)->where('parent_id', 0)->orderBy('stt', 'asc')->get();
            }),

            // 6. Trang tĩnh footer (Tag: pages)
            'footer_pages' => \App\Services\CacheService::remember('pages', 'frontend_footer_pages_data', $cacheTtl, function() {
                return Page::where('status', 1)->where('footer', 1)->orderBy('stt', 'asc')->get();
            }),

            // 7. Sản phẩm nổi bật (Tag: products)
            'products_hot' => \App\Services\CacheService::remember('products', 'frontend_products_hot_data', $cacheTtl, function() {
                return Product::where('status', 1)->where('hot', 1)->orderBy('stt', 'asc')->limit(10)->get();
            }),

            // 8. Toàn bộ cấu hình sản phẩm (Tag: products)
            'product_settings' => [
                'font_size' => \App\Models\ProductSetting::get('products_font_size', '16px'),
                'show_intro' => \App\Models\ProductSetting::get('products_show_intro', true),
                'click_image_detail' => \App\Models\ProductSetting::get('products_click_image_detail', true),
                'title_color' => \App\Models\ProductSetting::get('products_title_color', '#064e3b'),
                'category_color' => \App\Models\ProductSetting::get('products_category_color', '#b45309'),
            ],

            // 9. Toàn bộ cấu hình tin tức (Tag: news)
            'news_settings' => [
                'font_size' => \App\Models\NewsSetting::get('news_font_size', '16px'),
                'show_intro' => \App\Models\NewsSetting::get('news_show_intro', true),
                'click_image_detail' => \App\Models\NewsSetting::get('news_click_image_detail', true),
                'title_color' => \App\Models\NewsSetting::get('news_title_color', '#064e3b'),
                'category_color' => \App\Models\NewsSetting::get('news_category_color', '#b45309'),
            ],

            // Giỏ hàng (Session-based, không cache)
            'cart_count' => $this->cartService->getTotalItems(),
        ];

        $view->with($data);
    }
}