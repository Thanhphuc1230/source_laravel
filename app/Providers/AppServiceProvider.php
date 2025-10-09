<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\System;
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
            // 6 giờ (360 phút)
            $data['website'] = Cache::remember('website_data', 360, function () {
                return System::first();
            });

            // 6 giờ (360 phút) - đảm bảo nhất quán với cache trong MenuHelper
            $data['menu'] = Cache::remember('menu_header', 360, function () {
                return Menu::with('children')
                    ->where('parent_id', 0)
                    ->orderBy('stt', 'asc')
                    ->get();
            });
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
        \Illuminate\Support\Facades\Blade::if('hasAnyPermission', function (...$permissions) {
            return auth()->check() && auth()->user()->hasAnyPermission($permissions);
        });
    }
}
