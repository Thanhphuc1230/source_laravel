<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SitemapController;

Route::controller(SitemapController::class)
    ->prefix('sitemap')
    ->name('sitemap.')
    ->group(function () {
        Route::post('/create-sitemap', 'generate')->name('generate');
    });
