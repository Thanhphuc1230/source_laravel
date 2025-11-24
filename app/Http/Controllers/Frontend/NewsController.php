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

    public function categoryNews($id_cate_new)
    {
        $data = $this->newsService->getCategoryNewsData($id_cate_new);

        return view('frontend.modules.news.category', $data);
    }

    public function detailNews($id_news)
    {
        $data = $this->newsService->getDetailNewsData($id_news);

        return view('frontend.modules.news.detail', $data);
    }
}
