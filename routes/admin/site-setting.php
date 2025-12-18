<?php

use App\Http\Controllers\Admin\SiteSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(SiteSettingController::class)
    ->prefix('site-setting')
    ->name('site_setting.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:site_setting.view');
        Route::get('/{key}', 'show')->name('show')->where('key', 'homepage|trade-partner')->middleware('permission:site_setting.view');
        Route::post('/update-settings', 'updateSettings')->name('updateSettings')->middleware('permission:site_setting.edit');
    });