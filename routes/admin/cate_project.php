<?php

use App\Http\Controllers\Admin\CateProjectController;
use Illuminate\Support\Facades\Route;

Route::controller(CateProjectController::class)
    ->prefix('cate-project')
    ->name('cate-project.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:cate_project.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:cate_project.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:cate_project.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:cate_project.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:cate_project.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:cate_project.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:cate_project.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:cate_project.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:cate_project.edit');
    });
