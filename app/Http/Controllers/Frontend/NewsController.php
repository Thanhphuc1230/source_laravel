<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CateNew;
use App\Models\News;
class NewsController extends Controller
{
    public function categoryNews($slug_cate_new)
    {
        $data['category_detail'] = CateNew::where('status',1)->where('slug',$slug_cate_new)->firstOrFail();
        $data['news'] = News::where('category_id',$data['category_detail']->id_cate_new)->orderBy('created_at','desc')->paginate(8);
        return view('frontend.modules.news.category',$data);
    }

    public function detailNews($slug_news)
    {
        $data['news_detail'] = News::where('slug',$slug_news)->firstOrFail();
        $data['related_news'] = News::where('category_id',$data['news_detail']->category_id)->orderBy('created_at','desc')->limit(8)->get();
        return view('frontend.modules.news.detail',$data);
    }
}
