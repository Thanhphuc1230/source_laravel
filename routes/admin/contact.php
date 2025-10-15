<?php

use App\Http\Controllers\Admin\ContactController;
use Illuminate\Support\Facades\Route;

Route::controller(ContactController::class)
    ->prefix('contact')
    ->name('contact.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:contact.view');
        Route::get('/status/{uuid}/{status}/{name}', 'status')->name('status')->middleware('permission:contact.edit');
        Route::get('/edit/{uuid}', 'edit')->name('edit')->middleware('permission:contact.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:contact.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:contact.delete');
    });
