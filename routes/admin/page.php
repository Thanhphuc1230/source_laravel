<?php

use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;

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
