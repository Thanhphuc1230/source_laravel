<?php

namespace App\Services;

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
    public function getHomeData()
    {
        $data['sliders'] = Slider::where('status', 1)->orderBy('stt', 'asc')->get();

        $data['category_news'] = CateNew::where('status', 1)->where('home', 1)->orderBy('stt', 'asc')->get();

        $data['category_product'] = CateProduct::with('products')->where('status', 1)->where('home', 1)->orderBy('stt', 'asc')->get();

        $data['hot_products'] = Product::where('status', 1)->where('hot', 1)->orderBy('stt', 'asc')->limit(8)->get();

        $data['brands'] = Brand::where('status', 1)->orderBy('stt', 'asc')->get();

        $data['features'] = Feature::where('status', 1)->orderBy('stt', 'asc')->get();

        $data['latest_news'] = News::with('cate:id_cate_new,name_vn')
            ->select('id_new', 'name_vn', 'slug', 'image', 'intro_vn', 'created_at', 'category_id')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $data['galleries'] = Gallery::where('status', 1)->orderBy('stt', 'asc')->get();

        // Site Settings - Homepage
        $data['homepageSettings'] = SiteSetting::where('group', 'homepage')->get()->keyBy('key');

        // Trade Partner settings
        $data['tradePartner'] =  SiteSetting::where('group', 'trade_partner')->get()->keyBy('key');

        return $data;
    }
}