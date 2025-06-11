<?php

use Illuminate\Support\Facades\Route;
// admin
use App\Http\Controllers\Admin\CateProductController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CateNewController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\AnalyticController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SitemapController;
// frontend
use App\Http\Controllers\Frontend\HomeController;

// auth
use App\Http\Controllers\Auth\LoginController;

// login admin
Route::controller(LoginController::class)->group(function () {
    Route::get('/admintv', 'getLogin')->name('getLogin');
    Route::get('/admintv_logout', 'logout')->name('logout');
    Route::post('/admintv_post_login', 'postLogin')->name('postLogin');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group(['middleware' => 'web'], function () {
    Route::get('lang/{locale}', function ($locale = 'vn') {
        // Set default to 'vn'
        if (!in_array($locale, ['en', 'vn'])) {
            abort(404);
        }
        session()->put('locale', $locale);
        return redirect()->back();
    })->name('lang');
});

// sitemap
Route::get('/sitemap.xml', function() {
    return response()->file(public_path('sitemap.xml'));
});

// frontend
Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'home'])->name('home');
        // contact
        Route::get('/lien-he.html', [Contact::class, 'contact'])->name('contact');
        Route::post('/gui-yeu-cau-lien-he', [Contact::class, 'postContact'])->name('postContact');
        // subscribe
        Route::post('/subscribe', [Contact::class, 'postSubscribe'])->name('postSubscribe');
        // cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::get('/add-to-cart/{uuid}/{quantity?}', [CartController::class, 'addToCart'])->name('addToCart');
        Route::post('/update-cart', [CartController::class, 'updateCart'])->name('updateCart');
        // checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout-store', [CheckoutController::class, 'checkoutStore'])->name('checkoutStore');
        route::get('/order-success', [CheckoutController::class, 'orderSuccess'])->name('orderSuccess');
        // page
        Route::get('/page/{slug_page}.html', [Page::class, 'page'])->name('page');
        //Category Product
        Route::get('category/{slug_cate_product}.html', [Product::class, 'categoryProduct'])->name('categoryProduct');

        //Category News
        Route::get('news/{slug_cate_new}.html', [News::class, 'categoryNews'])->name('categoryNews');
        // detail news
        Route::get('news/{slug_cate_new}/{slug_news}.html', [News::class, 'detailNews'])->name('detailNews');
        // product
        Route::get('{slug_product}-{id_product}.html', function ($slug_product, $id_product) {
            return app(Product::class)->detailProduct($slug_product, $id_product);
        })
            ->where('slug_product', '.*')
            ->name('detailProduct');
    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware('checkAuth')
    ->group(function () {

        // Sitemap
        Route::controller(SitemapController::class)
            ->prefix('sitemap')
            ->name('sitemap.')
            ->group(function () {
                Route::post('/create-sitemap', 'generate')->name('generate');
            });
        // Analytics
        Route::controller(AnalyticController::class)
            ->prefix('analytics')
            ->name('analytics.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });
        // Profile admin
        Route::controller(ProfileController::class)
            ->prefix('profile')
            ->name('profile.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::post('/change_password', 'changePassword')->name('changePassword');
            });
        //Category Product
        Route::controller(CateProductController::class)
            ->prefix('cate_product')
            ->name('cate_product.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{name}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //Product
        Route::controller(ProductController::class)
            ->prefix('product')
            ->name('product.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{name}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
                Route::delete('/{uuid}/delete-image/{index}', 'deleteImage')->name('deleteImage');
            });
        //Category News
        Route::controller(CateNewController::class)
            ->prefix('cate_new')
            ->name('cate_new.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //News
        Route::controller(NewsController::class)
            ->prefix('news')
            ->name('news.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //Page
        Route::controller(PageController::class)
            ->prefix('page')
            ->name('page.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //Page
        Route::controller(SliderController::class)
            ->prefix('slider')
            ->name('slider.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
                Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
                Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
            });
        //menu
        Route::controller(MenuController::class)
            ->prefix('menu')
            ->name('menu.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/status/{uuid}/{status}', 'status')->name('status');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{uuid}', 'edit')->name('edit');
                Route::post('/update/{uuid}', 'update')->name('update');
                Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
            });
        // Quản lý hệ thống
        Route::controller(SystemController::class)
            ->prefix('system')
            ->name('system.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{id}', 'update')->name('update');
            });
    });

Route::get('/test-hello', function () {
    return '<h1>Hello Test</h1>';
});

// frontend
Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'home'])->name('home');
    });
