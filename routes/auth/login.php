<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::controller(LoginController::class)->group(function () {
    Route::get('/admintv', 'getLogin')->name('getLogin');
    Route::get('/admintv_logout', 'logout')->name('logout');
    Route::post('/admintv_post_login', 'postLogin')->name('postLogin');
});
