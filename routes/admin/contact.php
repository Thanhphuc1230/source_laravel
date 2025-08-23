<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContactController;

Route::controller(ContactController::class)
    ->prefix('contact')
    ->name('contact.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/status/{uuid}/{status}/{name}', 'status')->name('status');
        Route::get('/edit/{uuid}', 'edit')->name('edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
    });
