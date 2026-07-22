<?php

use Illuminate\Support\Facades\Route;

// Include utility routes
require __DIR__.'/utilities.php';

// Include auth routes
require __DIR__.'/auth/login.php';

// Include frontend routes
require __DIR__.'/frontend/home.php';
require __DIR__.'/frontend/cart.php';
require __DIR__.'/frontend/dynamic.php';

Route::prefix('admin')
    ->name('admin.')
    ->middleware('checkAuth')
    ->group(function () {
        // Include all admin route files
        require __DIR__.'/admin/sitemap.php';
        require __DIR__.'/admin/analytics.php';
        require __DIR__.'/admin/profile.php';
        require __DIR__.'/admin/cate-product.php';
        require __DIR__.'/admin/product.php';
        require __DIR__.'/admin/cate-news.php';
        require __DIR__.'/admin/news.php';
        require __DIR__.'/admin/page.php';
        require __DIR__.'/admin/slider.php';
        require __DIR__.'/admin/brand.php';
        require __DIR__.'/admin/feedback.php';
        require __DIR__.'/admin/feature.php';
        require __DIR__.'/admin/menu.php';
        require __DIR__.'/admin/system.php';
        require __DIR__.'/admin/order.php';
        require __DIR__.'/admin/contact.php';
        require __DIR__.'/admin/product-setting.php';
        require __DIR__.'/admin/news-setting.php';
        require __DIR__.'/admin/comment.php';
        require __DIR__.'/admin/user-role.php';
        require __DIR__.'/admin/fonts.php';
        require __DIR__.'/admin/chat.php';
        require __DIR__.'/admin/user.php';
        require __DIR__.'/admin/mail-config.php';
        require __DIR__.'/admin/mail-template.php';
        require __DIR__.'/admin/gallery.php';
    });
