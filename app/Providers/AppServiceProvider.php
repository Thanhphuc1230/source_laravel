<?php

namespace App\Providers;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Menu;
use App\Models\Page;
use App\Models\System;
use App\Models\Slider;
use App\Models\Product;
use App\Services\CartService;
use App\View\Composers\FrontendComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('frontend.*', FrontendComposer::class);

        // Clear frontend cache dynamically when data changes
        $clearFrontendCache = function() {
            if (class_exists(\App\Services\CacheService::class)) {
                \App\Services\CacheService::forgetTag('frontend');
                \App\Services\CacheService::forgetTag('system');
                \App\Services\CacheService::forgetTag('menu');
                \App\Services\CacheService::forgetTag('sliders');
                \App\Services\CacheService::forgetTag('categories');
                \App\Services\CacheService::forgetTag('pages');
                \App\Services\CacheService::forgetTag('products');
            }
            Cache::forget('frontend_global_data');
        };

        foreach ([System::class, Menu::class, Slider::class, CateProduct::class, CateNew::class, Page::class, Product::class] as $model) {
            $model::saved($clearFrontendCache);
            $model::deleted($clearFrontendCache);
        }
    }
}
