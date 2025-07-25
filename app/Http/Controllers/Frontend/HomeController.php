<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CateProduct;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Support\Facades\Cache;
use App\Models\News;
use App\Models\FeedBack;
class HomeController extends Controller
{
    public function home()
    {   
        // Cache all sliders
        $allSliders = Cache::remember('all_sliders', 60*60, function() {
            return Slider::where('status', 1)->orderBy('stt', 'asc')->get();
        });

        // Cache sliders for slider position
        $data['sliders'] = $allSliders->where('position', 1)->values();

        // Cache ads for ads position
        $data['ads'] = $allSliders->where('position', 3)->values();

        $data['cate_product'] = CateProduct::where('status', 1)->where('home', 1)->orderBy('stt', 'asc')->get();

        // Eager load cate
        $products = Product::with('cate')
            ->where('home', 1)
            ->where('status', 1)
            ->orderBy('stt', 'asc')
            ->get()
            ->groupBy('category_id');
        $data['products_by_category'] = $products;

        $data['products_hot'] = Product::with('cate')
            ->where('status', 1)
            ->where('hot', 1)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $data['products_sale'] = Product::with('cate')
            ->where('status', 1)
            ->where('sale', 1)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // lastest news
        $data['latest_news'] = News::with('cate')->where('status', 1)->orderBy('stt', 'asc')->get();
        // feedback
        $data['feedback'] = Cache::remember('feedback_cache', 60*60, function() {
            return FeedBack::where('status', 1)->orderBy('stt', 'asc')->get();
        });

        return view('frontend.modules.home.index', $data);
    }
}
