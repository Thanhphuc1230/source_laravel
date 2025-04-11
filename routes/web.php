<?php

use Illuminate\Support\Facades\Route;
// admin
use App\Http\Controllers\Admin\CateProductController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CateNewController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\AnalyticController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
// frontend
use App\Http\Controllers\Frontend\HomeController;

// auth
use App\Http\Controllers\Auth\LoginController;

// login admin
Route::controller(LoginController::class)->group(function () {
    Route::get('/admintv', 'getLogin')->name('getLogin');
    Route::get('/admintv_logout', 'logout')->name('logout');
    Route::post('/admintv_post_login', 'postLogin')->name('postLogin');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware('checkAuth')
    ->group(function () {
        // Analytics
        Route::controller(AnalyticController::class)
            ->prefix('analytics')
            ->name('analytics.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });
        //Category Product
        Route::controller(CateProductController::class)
            ->prefix('cate_product')
            ->name('cate_product.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{name}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //Product
        Route::controller(ProductController::class)
            ->prefix('product')
            ->name('product.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{name}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
                Route::delete('/{uuid}/delete-image/{index}', 'deleteImage')->name('deleteImage');
            });
        //Category News
        Route::controller(CateNewController::class)
            ->prefix('cate_new')
            ->name('cate_new.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //News
        Route::controller(NewsController::class)
            ->prefix('news')
            ->name('news.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //Page
        Route::controller(PageController::class)
            ->prefix('page')
            ->name('page.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //menu
        Route::controller(MenuController::class)
            ->prefix('menu')
            ->name('menu.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/status/{uuid}/{status}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
            });
    });

Route::get('/test-hello', function () {
    return '<h1>Hello Test</h1>';
});

// frontend
Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'home'])->name('home');
    });
