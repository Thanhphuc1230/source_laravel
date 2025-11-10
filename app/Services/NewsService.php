<?php

namespace App\Services;

use App\Models\CateNew;
use App\Models\CateProduct;
use App\Models\News;
use App\Models\Product;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;

class NewsService
{
    protected $newsRepository;
    protected $commentRepository;

    public function __construct(NewsRepositoryInterface $newsRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->newsRepository = $newsRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Get data for news category page
     *
     * @param string $slug_cate_new
     * @return array
     */
    public function getCategoryNewsData($slug_cate_new)
    {
        $data = [];

        // Get category detail
        $data['category_detail'] = CateNew::where('status', 1)
            ->where('slug', $slug_cate_new)
            ->select('id_cate_new', 'name_vn', 'slug', 'status')
            ->firstOrFail();

        // Get news in category
        $data['news'] = News::where('category_id', $data['category_detail']->id_cate_new)
            ->where('status', 1)
            ->select('id_new', 'name_vn', 'intro_vn', 'slug', 'image', 'created_at', 'category_id')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        // Get common data
        $data = array_merge($data, $this->getCommonFrontendData());

        return $data;
    }

    /**
     * Get data for news detail page
     *
     * @param string $slug_news
     * @return array
     */
    public function getDetailNewsData($slug_news)
    {
        $data = [];

        // Get news detail with category relationship
        $data['news_detail'] = News::with(['cate' => function ($query) {
            $query->select('id_cate_new', 'name_vn', 'slug');
        }])
            ->where('slug', $slug_news)
            ->select('id_new', 'name_vn', 'slug', 'image', 'content_vn', 'created_at', 'category_id', 'keywords', 'description')
            ->firstOrFail();

        // Get related news
        $data['related_news'] = News::where('category_id', $data['news_detail']->category_id)
            ->where('status', 1)
            ->where('id_new', '!=', $data['news_detail']->id_new)
            ->select('name_vn', 'slug', 'image', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get approved comments
        $data['comments'] = $this->commentRepository->getApprovedCommentsForItem('news', $data['news_detail']->id_new);

        // Get common data
        $data = array_merge($data, $this->getCommonFrontendData());

        return $data;
    }

    /**
     * Get common data used across frontend pages
     *
     * @return array
     */
    private function getCommonFrontendData()
    {
        return [
            'category_product' => CateProduct::where('status', 1)
                ->where('parent_id', 0)
                ->select('name_vn', 'status', 'slug')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get(),

            'product_hot' => Product::where('status', 1)
                ->where('hot', 1)
                ->select('name_vn', 'slug', 'image', 'price', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
    }
}