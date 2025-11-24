<?php

use App\Http\Controllers\Admin\MailTemplateController;
use Illuminate\Support\Facades\Route;

Route::controller(MailTemplateController::class)
    ->prefix('mail-template')
    ->name('mail-template.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:mail-template.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:mail-template.create');
        Route::post('/store', 'store')->name('store')->middleware('permission:mail-template.create');
        Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:mail-template.edit');
        Route::put('/update/{id}', 'update')->name('update')->middleware('permission:mail-template.edit');
        Route::post('/set-active/{id}', 'setActive')->name('set-active')->middleware('permission:mail-template.edit');
        Route::delete('/delete/{id}', 'destroy')->name('delete')->middleware('permission:mail-template.delete');
        Route::delete('/destroy-all', 'destroyAll')->name('destroyAll')->middleware('permission:mail-template.delete');
    });