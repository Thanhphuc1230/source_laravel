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

        $data['sliders'] = Cache::remember('slider_cache', config('cache.ttl.slider_cache', 3600), function() {
            return Slider::where('status', 1)->orderBy('stt', 'asc')->get();
        });

        $data['latest_news'] = Cache::remember('news_cache', config('cache.ttl.news_cache', 3600), function() {
            return News::where('status', 1)->orderBy('stt', 'asc')->get();
        });
        // Cache feedback using config-based TTL
        $data['feedback'] = Cache::remember('feedback_cache', config('cache.ttl.feedback_cache', 3600), function() {
            return FeedBack::where('status', 1)->orderBy('stt', 'asc')->get();
        });

        return view('frontend.modules.home.index', $data);
    }
}
