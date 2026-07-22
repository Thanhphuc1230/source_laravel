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
     * @param int $id_cate_new
     * @return array
     */
    public function getCategoryNewsData($id_cate_new)
    {
        $data = [];

        // Cache category detail
        $data['category_detail'] = CateNew::where('status', 1)
            ->where('id_cate_new', $id_cate_new)
            ->select('id_cate_new', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'status')
            ->firstOrFail();

        $perPage = (int) \App\Models\NewsSetting::get('news_pagination', 8);

        // Cache news in category
        $data['news'] = News::where('category_id', $data['category_detail']->id_cate_new)
            ->where('status', 1)
            ->select('id_new', 'name_vn', 'name_en', 'intro_vn', 'intro_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'created_at', 'category_id')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Get common data
        $data = array_merge($data, $this->getCommonFrontendData());

        return $data;
    }

    /**
     * Get data for news detail page
     *
     * @param int $id_news
     * @return array
     */
    public function getDetailNewsData($id_news)
    {
        $data = [];

        // Cache news detail with category relationship
        $data['news_detail'] = News::with(['cate' => function ($query) {
            $query->select('id_cate_new', 'name_vn', 'name_en', 'slug_vn', 'slug_en');
        }])
            ->where('id_new', $id_news)
            ->select('id_new', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'content_vn', 'content_en', 'created_at', 'category_id', 'keyword_vn', 'keyword_en', 'description_vn', 'description_en')
            ->firstOrFail();

        // Cache related news
        $data['related_news'] = News::where('category_id', $data['news_detail']->category_id)
            ->where('status', 1)
            ->where('id_new', '!=', $data['news_detail']->id_new)
            ->select('id_new', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Cache approved comments
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
                ->select('name_vn', 'name_en', 'status', 'slug_vn', 'slug_en')
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get(),

            'product_hot' => Product::where('status', 1)
                ->where('hot', 1)
                ->select('name_vn', 'name_en', 'slug_vn', 'slug_en', 'image_vn', 'image_en', 'price', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
    }
}