<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\NewsService;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function categoryNews($slug_cate_new)
    {
        $data = $this->newsService->getCategoryNewsData($slug_cate_new);

        return view('frontend.modules.news.category', $data);
    }

    public function detailNews($slug_news)
    {
        $data = $this->newsService->getDetailNewsData($slug_news);

        return view('frontend.modules.news.detail', $data);
    }
}
