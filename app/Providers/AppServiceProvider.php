<?php

namespace App\Providers;

use App\Models\Brand;
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

        View::composer('admin.*', function ($view) {
            if (class_exists(\App\Models\System::class)) {
                $system = Cache::remember('admin_system_config', now()->addHours(24), function() {
                    return \App\Models\System::first();
                });
                $activeLocales = $system->active_languages ?? ['vi', 'en'];
                $adminLanguages = [];
                if (in_array('vi', $activeLocales)) $adminLanguages[] = 'vn';
                if (in_array('en', $activeLocales)) $adminLanguages[] = 'en';

                $view->with([
                    'systemConfig' => $system,
                    'adminLanguages' => $adminLanguages
                ]);
            }
        });

        // Clear frontend cache dynamically when data changes
        $clearFrontendCache = function() {
            if (class_exists(\App\Services\CacheService::class)) {
                \App\Services\CacheService::forgetTags(['brands', 'frontend', 'system', 'menu', 'sliders', 'categories', 'pages', 'products']);
            }
            Cache::forget('frontend_global_data');
            Cache::forget('admin_system_config');
        };

        foreach ([System::class, Menu::class, Slider::class, CateProduct::class, CateNew::class, Page::class, Product::class, Brand::class] as $model) {
            $model::saved($clearFrontendCache);
            $model::deleted($clearFrontendCache);
        }
    }
}
