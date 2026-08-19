<?php

use App\Http\Controllers\Admin\CateServiceController;
use Illuminate\Support\Facades\Route;

Route::controller(CateServiceController::class)
    ->prefix('cate-service')
    ->name('cate-service.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:cate_service.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:cate_service.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:cate_service.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:cate_service.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:cate_service.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:cate_service.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:cate_service.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:cate_service.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:cate_service.edit');
    });
