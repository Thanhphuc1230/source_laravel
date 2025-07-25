<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\System;
use App\Models\Menu;
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

        View::composer('frontend.*', function ($view) {
            $data['website'] = Cache::remember('website_data', 60 * 6, function () {  // 6 giờ
                return System::first();
            });

            $data['menu'] = Cache::remember('menu_header', 60 * 6, function () {      // 2 giờ
                return Menu::with('children')
                    ->where('parent_id', 0)
                    ->orderBy('stt', 'asc')
                    ->get();
            });
            $view->with($data);
        });
    }
}
