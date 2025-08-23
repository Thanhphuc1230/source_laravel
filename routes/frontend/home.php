<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ContactController as Contact;

Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'home'])->name('home');
        
        // Contact
        Route::get('/lien-he.html', [Contact::class, 'contact'])->name('contact');
        Route::post('/gui-yeu-cau-lien-he', [Contact::class, 'postContact'])->name('postContact');
        
        // Subscribe
        Route::post('/subscribe', [Contact::class, 'postSubscribe'])->name('postSubscribe');
    });
