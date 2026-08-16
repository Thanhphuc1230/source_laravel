<?php

namespace App\Services;

use App\Models\About;
use App\Models\Brand;
use App\Models\Feature;
use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Gallery;
use App\Models\News;
use App\Models\SiteSetting;

class HomeService
{
    /**
     * Get home page data with caching
     * 
     * Note: Individual models use Cachable trait for automatic query caching.
     * This method groups multiple queries but each query is cached by the trait.
     * 
     * @return array
     */
    public function getHomeData()
    {
        $data = [];

        $data['sliders'] = Slider::where('status', 1)
            ->orderBy('stt', 'asc')
            ->get();

        $data['category_news'] = CateNew::where('status', 1)
            ->where('home', 1)
            ->orderBy('stt', 'asc')
            ->get();

        // Eager load products to prevent N+1
        $data['category_product'] = CateProduct::with(['products' => function($q) {
                $q->where('status', 1)->orderBy('stt', 'asc')->limit(8);
            }])
            ->where('status', 1)
            ->where('home', 1)
            ->orderBy('stt', 'asc')
            ->get();

        $data['hot_products'] = Product::where('status', 1)
            ->where('hot', 1)
            ->orderBy('stt', 'asc')
            ->limit(8)
            ->get();

        $data['latest_news'] = News::with('cate:id_cate_new,name_vn,name_en,slug_vn,slug_en')
            ->select('id_new', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'intro_vn', 'intro_en', 'created_at', 'category_id')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $data['about_section'] = About::where('status', 1)
            ->orderBy('stt', 'asc')
            ->orderBy('created_at', 'desc')
            ->first();

        $data['features'] = Feature::where('status', 1)
            ->orderBy('stt', 'asc')
            ->get();

        return $data;
    }
}