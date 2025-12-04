<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FileManagerController;

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

// Custom File Manager Routes
Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth', 'permission:system.view']], function () {
    Route::get('/', [FileManagerController::class, 'index'])->name('filemanager.index');
    Route::post('/upload', [FileManagerController::class, 'upload'])->name('filemanager.upload');
    Route::delete('/delete', [FileManagerController::class, 'delete'])->name('filemanager.delete');
    Route::post('/create-folder', [FileManagerController::class, 'createFolder'])->name('filemanager.create-folder');
    Route::get('/ckeditor', [FileManagerController::class, 'ckeditor'])->name('filemanager.ckeditor');
});
