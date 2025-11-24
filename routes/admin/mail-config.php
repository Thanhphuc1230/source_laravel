<?php

use App\Http\Controllers\Admin\MailConfigController;
use Illuminate\Support\Facades\Route;

Route::controller(MailConfigController::class)
    ->prefix('mail-config')
    ->name('mail-config.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:mail-config.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:mail-config.create');
        Route::post('/store', 'store')->name('store')->middleware('permission:mail-config.create');
        Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:mail-config.edit');
        Route::post('/update/{id}', 'update')->name('update')->middleware('permission:mail-config.edit');
        Route::post('/set-active/{id}', 'setActive')->name('set-active')->middleware('permission:mail-config.edit');
        Route::post('/test/{id}', 'testConfig')->name('test')->middleware('permission:mail-config.edit');
        Route::delete('/delete/{id}', 'destroy')->name('delete')->middleware('permission:mail-config.delete');
    });