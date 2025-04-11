<?php

use Illuminate\Support\Facades\Route;
// admin
use App\Http\Controllers\Admin\CateProductController;
use App\Http\Controllers\Admin\ProductController;
// frontend
use App\Http\Controllers\Frontend\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
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
    });

    Route::get('/test-hello', function() {
        return '<h1>Hello Test</h1>';
    });

// frontend
Route::name('web.')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
});
