<?php

use Illuminate\Support\Facades\Route;

// Language switching
Route::group(['middleware' => 'web'], function () {
    Route::get('lang/{locale}', function ($locale = 'vn') {
        // Set default to 'vn'
        if (! in_array($locale, ['en', 'vn'])) {
            abort(404);
        }
        session()->put('locale', $locale);

        return redirect()->back();
    })->name('lang');
});

// Sitemap
Route::get('/sitemap.xml', function () {
    return response()->file(public_path('sitemap.xml'));
});

// Laravel File Manager
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
