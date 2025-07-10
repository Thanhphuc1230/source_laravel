<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\News;
use App\Models\CateProduct;
use App\Models\CateNew;
use App\Models\Page;

class RouteController extends Controller
{
    /**
     * Universal route handler - xử lý tất cả slug với fallback chain
     * Priority: Product Detail > News Detail > Category Product > Category News > Page
     */
    public function resolve($slug)
    {
        // 1. Kiểm tra Product Detail trước (ưu tiên cao nhất)
        $product = Product::where('slug', $slug)->where('status', 1)->first();
        if ($product) {
            return app(ProductController::class)->detailProduct($slug);
        }
        
        // 2. Kiểm tra News Detail
        $news = News::where('slug', $slug)->where('status', 1)->first();
        if ($news) {
            return app(NewsController::class)->detailNews($slug);
        }
        
        // 3. Kiểm tra Category Product
        $cateProduct = CateProduct::where('slug', $slug)->where('status', 1)->first();
        if ($cateProduct) {
            return app(ProductController::class)->categoryProduct($slug);
        }
        
        // 4. Kiểm tra Category News
        $cateNews = CateNew::where('slug', $slug)->where('status', 1)->first();
        if ($cateNews) {
            return app(NewsController::class)->categoryNews($slug);
        }
        
        // 5. Kiểm tra Page (ưu tiên thấp nhất)
        $page = Page::where('slug', $slug)->where('status', 1)->first();
        if ($page) {
            return app(PageController::class)->page($slug);
        }
        
        // 6. Không tìm thấy gì - 404
        return view('errors.404');
    }
}
