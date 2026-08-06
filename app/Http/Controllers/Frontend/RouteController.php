<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RouteController extends Controller
{
    /**
     * Universal route handler - xử lý tất cả slug với fallback chain
     * Priority: Product Detail > News Detail > Category Product > Category News > Page
     */
    public function resolve($slug)
    {
        // Cache key cho slug resolution
        $cacheKey = "slug_resolution_{$slug}";

        // Cache TTL từ config hoặc default 1 giờ
        $cacheTtl = config('cache.ttl.slug_resolution', 3600);

        // Kiểm tra cache trước
        if (class_exists(CacheService::class)) {
            $result = CacheService::remember(
                CacheService::TAGS['frontend'] ?? 'frontend',
                $cacheKey,
                $cacheTtl,
                function () use ($slug) {
                    return $this->findContentBySlug($slug);
                }
            );
        } else {
            $result = Cache::remember($cacheKey, $cacheTtl, function () use ($slug) {
                return $this->findContentBySlug($slug);
            });
        }

        if (! $result) {
            return view('errors.404');
        }

        return $this->dispatchToController($result);
    }

    /**
     * Tìm content bằng 1 query UNION để tối ưu tốc độ (Hỗ trợ 2 ngôn ngữ VN & EN)
     * Thứ tự ưu tiên: product > news > cate_product > cate_news > page
     */
    private function findContentBySlug(string $slug): ?array
    {
        try {
            $locale = app()->getLocale();
            if (! in_array($locale, ['vn', 'en'], true)) {
                $locale = 'vn';
            }

            $slugColumn = "slug_{$locale}";
            $titleColumn = "name_{$locale}";

            $query = "
                SELECT 'product' as type, id_product as id, {$slugColumn} as slug, {$titleColumn} as title
                FROM tp_products WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                UNION ALL
                SELECT 'news' as type, id_new as id, {$slugColumn} as slug, {$titleColumn} as title
                FROM tp_news WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                UNION ALL
                SELECT 'cate_product' as type, id_cate_product as id, {$slugColumn} as slug, {$titleColumn} as title
                FROM tp_cate_products WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                UNION ALL
                SELECT 'cate_news' as type, id_cate_new as id, {$slugColumn} as slug, {$titleColumn} as title
                FROM tp_cate_news WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                UNION ALL
                SELECT 'page' as type, id_page as id, {$slugColumn} as slug, {$titleColumn} as title
                FROM tp_pages WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ?) AND status = 1
                UNION ALL
                SELECT 'brand' as type, id_brand as id, {$titleColumn} as slug, {$titleColumn} as title
                FROM tp_brands WHERE ({$slugColumn} = ? OR slug_vn = ? OR slug_en = ? OR name_vn = ?) AND status = 1
                LIMIT 1
            ";
            
            $params = array_fill(0, 19, $slug);
            $result = DB::select($query, $params);
            
            if ($result) {
                return (array) $result[0];
            }

            return null;

        } catch (\Exception $e) {
            Log::error("Error finding content by slug '{$slug}': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Dispatch đến controller tương ứng
     */
    private function dispatchToController(array $content): mixed
    {
        try {
            switch ($content['type']) {
                case 'product':
                    return app(ProductController::class)->detailProduct($content['id']);

                case 'news':
                    return app(NewsController::class)->detailNews($content['id']);

                case 'cate_product':
                    return app(ProductController::class)->categoryProduct($content['id']);

                case 'cate_news':
                    return app(NewsController::class)->categoryNews($content['id']);

                case 'page':
                    return app(PageController::class)->page($content['id']);

                case 'brand':
                    return app(ProductController::class)->brandProduct($content['id']);

                default:
                    return view('errors.404');
            }
        } catch (\Exception $e) {
            Log::error("Error dispatching controller for type '{$content['type']}': " . $e->getMessage());
            return view('errors.404');
        }
    }
}
