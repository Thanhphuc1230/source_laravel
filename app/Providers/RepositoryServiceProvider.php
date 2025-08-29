<?php

namespace App\Providers;

use App\Repositories\Eloquent\CateNewRepository;
use App\Repositories\Eloquent\CateProductRepository;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Interfaces\CateNewRepositoryInterface;
use App\Repositories\Interfaces\CateProductRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(CateProductRepositoryInterface::class, CateProductRepository::class);
        $this->app->bind(CateNewRepositoryInterface::class, CateNewRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
