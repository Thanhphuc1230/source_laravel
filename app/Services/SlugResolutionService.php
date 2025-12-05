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
     * Priority: Product > News > CateProduct > CateNews > Page
     */
    public function findContentBySlug(string $slug): ?array
    {
        try {
            // Lấy ID từ cuối slug (sau dấu gạch ngang cuối cùng)
            $id = null;
            $lastDashPos = strrpos($slug, '-');

            if ($lastDashPos !== false) {
                $possibleId = substr($slug, $lastDashPos + 1);
                if (is_numeric($possibleId)) {
                    $id = $possibleId;
                }
            }

            // Nếu có ID, tìm theo ID; nếu không có ID, tìm theo slug
            if ($id) {
                $query = "
                    SELECT 'product' as type, id_product as id, slug, name_vn as title
                    FROM tp_products WHERE id_product = ? AND status = 1
                    UNION ALL
                    SELECT 'news' as type, id_new as id, slug, name_vn as title
                    FROM tp_news WHERE id_new = ? AND status = 1
                    UNION ALL
                    SELECT 'cate_product' as type, id_cate_product as id, slug, name_vn as title
                    FROM tp_cate_products WHERE id_cate_product = ? AND status = 1
                    UNION ALL
                    SELECT 'cate_news' as type, id_cate_new as id, slug, name_vn as title
                    FROM tp_cate_news WHERE id_cate_new = ? AND status = 1
                    UNION ALL
                    SELECT 'page' as type, id_page as id, slug, name_vn as title
                    FROM tp_pages WHERE id_page = ? AND status = 1
                    LIMIT 1
                ";
                $params = [$id, $id, $id, $id, $id];
            } else {
                $query = "
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
                    UNION ALL
                    SELECT 'page' as type, id_page as id, slug, name_vn as title
                    FROM tp_pages WHERE slug = ? AND status = 1
                    LIMIT 1
                ";
                $params = [$slug, $slug, $slug, $slug, $slug];
            }

            $result = DB::select($query, $params);
            return $result ? (array) $result[0] : null;

        } catch (\Exception $e) {
            return null;
        }
    }
}