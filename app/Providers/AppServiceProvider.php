<?php

namespace App\Providers;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Menu;
use App\Models\Page;
use App\Models\System;
use App\Services\CacheService;
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
    }
}
