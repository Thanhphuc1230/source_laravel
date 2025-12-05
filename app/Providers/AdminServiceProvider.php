<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    /**
     * Đăng ký Custom Blade Directives cho admin
     */
    protected function registerBladeDirectives(): void
    {
        // @hasRole directive
        Blade::if('hasRole', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // @hasPermission directive
        Blade::if('hasPermission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        // @hasAnyRole directive
        Blade::if('hasAnyRole', function (...$roles) {
            return auth()->check() && auth()->user()->hasRole($roles);
        });

        // @hasAnyPermission directive
        Blade::if('hasAnyPermission', function ($permissions) {
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