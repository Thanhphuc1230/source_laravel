<?php

use App\Http\Controllers\Admin\ChatController;
use Illuminate\Support\Facades\Route;

Route::controller(ChatController::class)
    ->prefix('chat')
    ->name('chat.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:chat.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:chat.create');
        Route::post('/store', 'store')->name('store')->middleware('permission:chat.create');
        Route::get('/show/{uuid}', 'show')->name('show')->middleware('permission:chat.view');
        Route::get('/edit/{uuid}', 'edit')->name('edit')->middleware('permission:chat.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:chat.edit');
        Route::post('/close/{uuid}', 'close')->name('close')->middleware('permission:chat.edit');
        Route::post('/bulk-close', 'bulkClose')->name('bulk-close')->middleware('permission:chat.edit');
        Route::delete('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:chat.delete');
        Route::post('/send/{uuid}', 'send')->name('send')->middleware('permission:chat.view');
        Route::get('/messages/{uuid}', 'messages')->name('messages')->middleware('permission:chat.view');
        Route::get('/sessions', 'getSessions')->name('sessions')->middleware('permission:chat.view');
        Route::get('/session/{sessionId}', 'getSession')->name('session')->middleware('permission:chat.view');
        Route::post('/send-message', 'sendMessage')->name('send-message')->middleware('permission:chat.view');
        Route::post('/mark-read/{sessionId}', 'markAsRead')->name('mark-read')->middleware('permission:chat.view');
        Route::post('/end-session/{sessionId}', 'endSession')->name('end-session')->middleware('permission:chat.edit');
        Route::get('/unread-count', 'getUnreadCount')->name('unread-count')->middleware('permission:chat.view');
    });