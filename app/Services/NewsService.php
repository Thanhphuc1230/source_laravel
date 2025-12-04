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
        $data['category_detail'] = \App\Services\CacheService::remember(
            \App\Services\CacheService::TAGS['news'] ?? 'news',
            "category_news_detail_{$id_cate_new}",
            \App\Services\CacheService::getTtl('long'),
            fn () => CateNew::where('status', 1)
                ->where('id_cate_new', $id_cate_new)
                ->select('id_cate_new', 'name_vn', 'slug', 'status')
                ->firstOrFail()
        );

        // Cache news in category
        $data['news'] = \App\Services\CacheService::remember(
            \App\Services\CacheService::TAGS['news'] ?? 'news',
            "category_news_list_{$id_cate_new}",
            \App\Services\CacheService::getTtl('medium'),
            fn () => News::where('category_id', $data['category_detail']->id_cate_new)
                ->where('status', 1)
                ->select('id_new', 'name_vn', 'intro_vn', 'slug', 'image', 'created_at', 'category_id')
                ->orderBy('created_at', 'desc')
                ->paginate(8)
        );

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
        $data['news_detail'] = \App\Services\CacheService::remember(
            \App\Services\CacheService::TAGS['news'] ?? 'news',
            "news_detail_{$id_news}",
            \App\Services\CacheService::getTtl('long'),
            fn () => News::with(['cate' => function ($query) {
                $query->select('id_cate_new', 'name_vn', 'slug');
            }])
                ->where('id_new', $id_news)
                ->select('id_new', 'name_vn', 'slug', 'image', 'content_vn', 'created_at', 'category_id', 'keywords', 'description')
                ->firstOrFail()
        );

        // Cache related news
        $data['related_news'] = \App\Services\CacheService::remember(
            \App\Services\CacheService::TAGS['news'] ?? 'news',
            "related_news_{$id_news}",
            \App\Services\CacheService::getTtl('medium'),
            fn () => News::where('category_id', $data['news_detail']->category_id)
                ->where('status', 1)
                ->where('id_new', '!=', $data['news_detail']->id_new)
                ->select('id_new', 'name_vn', 'slug', 'image', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get()
        );

        // Cache approved comments
        $data['comments'] = \App\Services\CacheService::remember(
            \App\Services\CacheService::TAGS['comments'] ?? 'comments',
            "news_comments_{$data['news_detail']->id_new}",
            \App\Services\CacheService::getTtl('medium'),
            fn () => $this->commentRepository->getApprovedCommentsForItem('news', $data['news_detail']->id_new)
        );

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
            'category_product' => \App\Services\CacheService::remember(
                \App\Services\CacheService::TAGS['categories'] ?? 'categories',
                'common_category_product',
                \App\Services\CacheService::getTtl('long'),
                fn () => CateProduct::where('status', 1)
                    ->where('parent_id', 0)
                    ->select('name_vn', 'status', 'slug')
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get()
            ),

            'product_hot' => \App\Services\CacheService::remember(
                \App\Services\CacheService::TAGS['products'] ?? 'products',
                'common_product_hot',
                \App\Services\CacheService::getTtl('medium'),
                fn () => Product::where('status', 1)
                    ->where('hot', 1)
                    ->select('name_vn', 'slug', 'image', 'price', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
            )
        ];
    }
}