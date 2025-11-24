<?php

namespace App\Providers;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Menu;
use App\Models\System;
use App\Services\CacheService;
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
        // Register RateLimitService with factory methods
        $this->app->singleton(\App\Services\RateLimitService::class, function ($app) {
            // Default service (can be overridden with factory methods)
            return new \App\Services\RateLimitService(
                prefix: 'default',
                maxAttempts: config('auth.rate_limit.max_attempts', 5),
                decayMinutes: config('auth.rate_limit.decay_minutes', 15)
            );
        });

        // Register Repositories
        $this->app->bind(
            \App\Repositories\MailTemplateRepositoryInterface::class,
            \App\Repositories\MailTemplateRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Đăng ký Blade directives cho permission system
        $this->registerBladeDirectives();

        View::composer('frontend.*', function ($view) {
            // Website data with tags
            $data['website'] = CacheService::remember(
                CacheService::TAGS['website'],
                'website_data',
                CacheService::getTtl('long'),
                function () {
                    return System::first();
                }
            );

            // Menu data with tags
            $data['menu'] = CacheService::remember(
                CacheService::TAGS['menu'],
                'menu_header',
                CacheService::getTtl('long'),
                function () {
                    return Menu::with('children')
                        ->where('parent_id', 0)
                        ->orderBy('stt', 'asc')
                        ->get();
                }
            );

            // Danh mục sản phẩm cho footer
            $data['category_product_footer'] = CacheService::remember(
                CacheService::TAGS['categories'],
                'category_product_footer',
                CacheService::getTtl('long'),
                function () {
                    return CateProduct::where('status', 1)
                        ->whereIn('parent_id', [0,1])
                        ->orderBy('stt', 'asc')
                        ->get();
                }
            );

            // Danh mục tin tức cho footer
            $data['category_news_footer'] = CacheService::remember(
                CacheService::TAGS['categories'],
                'category_news_footer',
                CacheService::getTtl('long'),
                function () {
                    return CateNew::where('status', 1)
                        ->where('parent_id', 0)
                        ->orderBy('stt', 'asc')
                        ->get();
                }
            );

            $view->with($data);
        });
    }

    /**
     * Đăng ký Custom Blade Directives
     */
    protected function registerBladeDirectives(): void
    {
        // @hasRole directive
        \Illuminate\Support\Facades\Blade::if('hasRole', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // @hasPermission directive
        \Illuminate\Support\Facades\Blade::if('hasPermission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        // @hasAnyRole directive
        \Illuminate\Support\Facades\Blade::if('hasAnyRole', function (...$roles) {
            return auth()->check() && auth()->user()->hasRole($roles);
        });

        // @hasAnyPermission directive
        \Illuminate\Support\Facades\Blade::if('hasAnyPermission', function ($permissions) {
            if (!auth()->check()) {
                return false;
            }
            
            // Convert single permission to array
            if (is_string($permissions)) {
                $permissions = [$permissions];
            }
            
            return auth()->user()->hasAnyPermission($permissions);
        });
    }
}
