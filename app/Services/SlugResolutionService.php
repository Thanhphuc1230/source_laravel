<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Repositories\Interfaces\CateProductRepositoryInterface;
use App\Repositories\Interfaces\CateNewRepositoryInterface;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SlugResolutionService
{
    protected $productRepository;
    protected $newsRepository;
    protected $cateProductRepository;
    protected $cateNewRepository;
    protected $pageRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        NewsRepositoryInterface $newsRepository,
        CateProductRepositoryInterface $cateProductRepository,
        CateNewRepositoryInterface $cateNewRepository,
        PageRepositoryInterface $pageRepository
    ) {
        $this->productRepository = $productRepository;
        $this->newsRepository = $newsRepository;
        $this->cateProductRepository = $cateProductRepository;
        $this->cateNewRepository = $cateNewRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Tìm content bằng slug với priority chain
     * Priority: Page > Product > News > CateProduct > CateNews
     * 
     * Note: Slug đã được format với ID (vd: san-pham-1) nên đảm bảo unique toàn hệ thống
     */
    public function findContentBySlug(string $slug): ?array
    {
        try {
            // Tìm theo slug trực tiếp - slug đã là unique với format name-id
            $query = "
                SELECT 'page' as type, id_page as id, slug, name_vn as title
                FROM tp_pages WHERE slug = ? AND status = 1
                UNION ALL
                SELECT 'product' as type, id_product as id, slug, name_vn as title
                FROM tp_products WHERE slug = ? AND status = 1
                UNION ALL
                SELECT 'news' as type, id_new as id, slug, name_vn as title
                FROM tp_news WHERE slug = ? AND status = 1
                UNION ALL
                SELECT 'cate_product' as type, id_cate_product as id, slug, name_vn as title
                FROM tp_cate_products WHERE slug = ? AND status = 1
                UNION ALL
                SELECT 'cate_news' as type, id_cate_new as id, slug, name_vn as title
                FROM tp_cate_news WHERE slug = ? AND status = 1
                LIMIT 1
            ";
            $params = [$slug, $slug, $slug, $slug, $slug];

            $result = DB::select($query, $params);
            return $result ? (array) $result[0] : null;

        } catch (\Exception $e) {
            return null;
        }
    }
}