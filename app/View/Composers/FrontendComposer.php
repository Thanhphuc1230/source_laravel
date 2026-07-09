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
        // Cache frontend global queries to optimize speed and DB queries
        $globalData = Cache::remember('frontend_global_data', now()->addMinutes(120), function() {
            return [
                // Website data
                'website' => System::first(),

                // Menu data
                'menu' => Menu::with('children')
                    ->where('parent_id', 0)
                    ->orderBy('stt', 'asc')
                    ->get(),

                // Ads (sliders)
                'ads' => Slider::where('status', 1)
                    ->orderBy('stt', 'asc')
                    ->get(),

                // Category product
                'cate_product' => CateProduct::where('status', 1)
                    ->where('parent_id', 0)
                    ->orderBy('stt', 'asc')
                    ->get(),

                // Category product footer
                'category_product_footer' => CateProduct::where('status', 1)
                    ->whereIn('parent_id', [0, 1])
                    ->orderBy('stt', 'asc')
                    ->get(),

                // Category news footer
                'category_news_footer' => CateNew::where('status', 1)
                    ->where('parent_id', 0)
                    ->orderBy('stt', 'asc')
                    ->get(),

                // Footer pages
                'footer_pages' => Page::where('status', 1)
                    ->where('footer', 1)
                    ->orderBy('stt', 'asc')
                    ->get(),

                // Products hot
                'products_hot' => Product::where('status', 1)
                    ->where('hot', 1)
                    ->orderBy('stt', 'asc')
                    ->limit(10)
                    ->get(),
            ];
        });

        // Merge cached global data with request-specific data
        $data = array_merge($globalData, [
            // Cart count (session-based, cannot be cached globally)
            'cart_count' => $this->cartService->getTotalItems(),
        ]);

        $view->with($data);
    }
}