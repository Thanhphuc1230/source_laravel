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
use Illuminate\Support\Facades\Cache;
use App\Services\CacheService;

class HomeService
{
    public function getHomeData()
    {
        $data['sliders'] = CacheService::remember(
            CacheService::TAGS['sliders'],
            'slider_cache',
            CacheService::getTtl('long'),
            function () {
                return Slider::where('status', 1)->orderBy('stt', 'asc')->get();
            }
        );

        $data['category_news'] = CacheService::remember(
            CacheService::TAGS['news'],
            'category_news_home',
            CacheService::getTtl('long'),
            function () {
                return CateNew::where('status', 1)->where('home', 1)->orderBy('stt', 'asc')->get();
            }
        );

        $data['category_product'] = CacheService::remember(
            CacheService::TAGS['products'],
            'category_product_home',
            CacheService::getTtl('long'),
            function () {
                return CateProduct::with('products')->where('status', 1)->where('home', 1)->orderBy('stt', 'asc')->get();
            }
        );

        $data['hot_products'] = CacheService::remember(
            CacheService::TAGS['products'],
            'hot_products_home',
            CacheService::getTtl('medium'),
            function () {
                return Product::with('cate')
                    ->select('id_product', 'uuid', 'name_vn', 'slug', 'price', 'image', 'intro_vn', 'category_id', 'status', 'stt', 'created_at')
                    ->where('status', 1)
                    ->where('hot', 1)
                    ->orderBy('created_at', 'desc')
                    ->limit(7)
                    ->get();
            }
        );

        $data['brands'] = CacheService::remember(
            CacheService::TAGS['brands'],
            'brands_cache',
            CacheService::getTtl('long'),
            function () {
                return Brand::where('status', 1)->orderBy('stt', 'asc')->get();
            }
        );

        $data['features'] = CacheService::remember(
            CacheService::TAGS['features'],
            'features_cache',
            CacheService::getTtl('long'),
            function () {
                return Feature::where('status', 1)->orderBy('stt', 'asc')->get();
            }
        );

        $data['latest_news'] = CacheService::remember(
            CacheService::TAGS['news'],
            'latest_news_home',
            CacheService::getTtl('medium'),
            function () {
                return News::with('cate:id_cate_new,name_vn')
                    ->select('id_new', 'name_vn', 'slug', 'image', 'intro_vn', 'created_at', 'category_id')
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            }
        );

        $data['galleries'] = CacheService::remember(
            CacheService::TAGS['galleries'],
            'galleries_home',
            CacheService::getTtl('long'),
            function () {
                return Gallery::where('status', 1)->orderBy('stt', 'asc')->get();
            }
        );

        // Site Settings - Homepage
        $data['homepageSettings'] = SiteSetting::where('group', 'homepage')->get()->keyBy('key');

        // Trade Partner settings
        $data['tradePartner'] =  SiteSetting::where('group', 'trade_partner')->get()->keyBy('key');

        return $data;
    }
}