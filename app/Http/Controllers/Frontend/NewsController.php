<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CateNew;
use App\Models\News;
use App\Models\CateProduct;
class NewsController extends Controller
{
    public function categoryNews($slug_cate_new)
    {
        $data['category_detail'] = CateNew::where('status',1)
            ->where('slug',$slug_cate_new)
            ->select('id_cate_new', 'name_vn', 'slug', 'status')
            ->firstOrFail();
            
        $data['news'] = News::where('category_id',$data['category_detail']->id_cate_new)
            ->where('status', 1)
            ->select('id_new', 'name_vn', 'slug', 'image', 'created_at', 'category_id')
            ->orderBy('created_at','desc')
            ->paginate(8);
            
        return view('frontend.modules.news.category',$data);
    }

    public function detailNews($slug_news)
    {
        // Load news detail với category
        $data['news_detail'] = News::with(['cate' => function($query) {
                $query->select('id_cate_new', 'name_vn', 'slug');
            }])
            ->where('slug',$slug_news)
            ->select('name_vn', 'slug', 'image', 'content_vn', 'created_at', 'category_id')
            ->firstOrFail();
            
        // Load related news KHÔNG eager load category (vì đã có category info từ news_detail)
        $data['related_news'] = News::where('category_id',$data['news_detail']->category_id)
            ->where('status',1)
            ->where('id_new', '!=', $data['news_detail']->id_new)
            ->select('name_vn','slug','image','created_at')
            ->orderBy('created_at','desc')
            ->limit(5)
            ->get();

        $data['category_product'] = CateProduct::where('status',1)
            ->where('parent_id',0)
            ->select('name_vn','status','slug')
            ->orderBy('created_at','desc')
            ->limit(8)
            ->get();
            
        return view('frontend.modules.news.detail',$data);
    }
}
