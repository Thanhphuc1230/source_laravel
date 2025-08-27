<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
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
        $result = Cache::remember($cacheKey, $cacheTtl, function () use ($slug) {
            return $this->findContentBySlug($slug);
        });

        if (! $result) {
            Log::warning('Slug not found', [
                'slug' => $slug,
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
            ]);

            return view('errors.404');
        }

        // Log successful resolution
        Log::info('Slug resolved successfully', [
            'slug' => $slug,
            'type' => $result['type'],
            'title' => $result['title'],
        ]);

        // Dispatch đến controller tương ứng
        return $this->dispatchToController($result);
    }

    /**
     * Tìm content bằng single query với UNION
     */
    private function findContentBySlug(string $slug): ?array
    {
        try {
            $query = "
                SELECT 'product' as type, id_product as id, slug, name_vn as title, status
                FROM tp_products 
                WHERE slug = ? AND status = 1
                UNION ALL
                SELECT 'news' as type, id_new as id, slug, name_vn as title, status  
                FROM tp_news 
                WHERE slug = ? AND status = 1
                UNION ALL
                SELECT 'cate_product' as type, id_cate_product as id, slug, name_vn as title, status
                FROM tp_cate_products 
                WHERE slug = ? AND status = 1
                UNION ALL
                SELECT 'cate_news' as type, id_cate_new as id, slug, name_vn as title, status
                FROM tp_cate_news 
                WHERE slug = ? AND status = 1
                UNION ALL
                SELECT 'page' as type, id_page as id, slug, name_vn as title, status
                FROM tp_pages 
                WHERE slug = ? AND status = 1
                ORDER BY 
                    CASE type 
                        WHEN 'product' THEN 1
                        WHEN 'news' THEN 2
                        WHEN 'cate_product' THEN 3
                        WHEN 'cate_news' THEN 4
                        WHEN 'page' THEN 5
                    END
                LIMIT 1
            ";

            $result = DB::select($query, [$slug, $slug, $slug, $slug, $slug]);

            return $result ? (array) $result[0] : null;

        } catch (\Exception $e) {
            Log::error('Error finding content by slug', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);

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
                    return app(ProductController::class)->detailProduct($content['slug']);

                case 'news':
                    return app(NewsController::class)->detailNews($content['slug']);

                case 'cate_product':
                    return app(ProductController::class)->categoryProduct($content['slug']);

                case 'cate_news':
                    return app(NewsController::class)->categoryNews($content['slug']);

                case 'page':
                    return app(PageController::class)->page($content['slug']);

                default:
                    Log::warning('Unknown content type', [
                        'type' => $content['type'],
                        'slug' => $content['slug'],
                    ]);

                    return view('errors.404');
            }
        } catch (\Exception $e) {
            Log::error('Error dispatching to controller', [
                'type' => $content['type'],
                'slug' => $content['slug'],
                'error' => $e->getMessage(),
            ]);

            return view('errors.404');
        }
    }
}
