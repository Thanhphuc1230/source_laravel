<?php

use App\Http\Controllers\Admin\CateNewController;
use Illuminate\Support\Facades\Route;

Route::controller(CateNewController::class)
    ->prefix('cate_new')
    ->name('cate_new.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:cate_news.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:cate_news.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:cate_news.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:cate_news.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:cate_news.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:cate_news.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:cate_news.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:cate_news.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:cate_news.edit');
    });
