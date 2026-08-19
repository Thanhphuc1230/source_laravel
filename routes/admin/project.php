<?php

use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

Route::controller(ProjectController::class)
    ->prefix('project')
    ->name('project.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:project.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:project.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:project.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:project.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:project.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:project.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:project.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:project.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:project.edit');
    });
