<?php

use App\Http\Controllers\Admin\SitemapController;
use Illuminate\Support\Facades\Route;

Route::controller(SitemapController::class)
    ->prefix('sitemap')
    ->name('sitemap.')
    ->group(function () {
        Route::post('/create-sitemap', 'generate')->name('generate');
    });
