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
    public function resolve($id, $slug)
    {
        // Validate ID is numeric
        if (!is_numeric($id)) {
            return view('errors.404');
        }

        // Cache key cho slug resolution
        $cacheKey = "slug_resolution_{$id}_{$slug}";

        // Cache TTL từ config hoặc default 1 giờ
        $cacheTtl = config('cache.ttl.slug_resolution', 3600);

        // Kiểm tra cache trước
        $result = CacheService::remember(
            CacheService::TAGS['frontend'] ?? 'frontend',
            $cacheKey,
            $cacheTtl,
            function () use ($id, $slug) {
                return $this->findContentByIdAndSlug($id, $slug);
            }
        );

        if (! $result) {
            Log::warning('Content not found by ID and slug', [
                'id' => $id,
                'slug' => $slug,
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
            ]);

            return view('errors.404');
        }

        // Log successful resolution
        Log::info('Content resolved successfully', [
            'id' => $id,
            'slug' => $slug,
            'type' => $result['type'],
            'title' => $result['title'],
        ]);

        // Dispatch đến controller tương ứng
        return $this->dispatchToController($result);
    }

    /**
     * Tìm content bằng ID và slug với single query
     */
    private function findContentByIdAndSlug(int $id, string $slug): ?array
    {
        try {
            $query = "
                SELECT 'product' as type, id_product as id, slug, name_vn as title, status
                FROM tp_products
                WHERE id_product = ? AND slug = ? AND status = 1
                UNION ALL
                SELECT 'news' as type, id_new as id, slug, name_vn as title, status
                FROM tp_news
                WHERE id_new = ? AND slug = ? AND status = 1
                UNION ALL
                SELECT 'cate_product' as type, id_cate_product as id, slug, name_vn as title, status
                FROM tp_cate_products
                WHERE id_cate_product = ? AND slug = ? AND status = 1
                UNION ALL
                SELECT 'cate_news' as type, id_cate_new as id, slug, name_vn as title, status
                FROM tp_cate_news
                WHERE id_cate_new = ? AND slug = ? AND status = 1
                UNION ALL
                SELECT 'page' as type, id_page as id, slug, name_vn as title, status
                FROM tp_pages
                WHERE id_page = ? AND slug = ? AND status = 1
                LIMIT 1
            ";

            $result = DB::select($query, [$id, $slug, $id, $slug, $id, $slug, $id, $slug, $id, $slug]);

            return $result ? (array) $result[0] : null;

        } catch (\Exception $e) {
            Log::error('Error finding content by ID and slug', [
                'id' => $id,
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
                    return app(ProductController::class)->detailProduct($content['id']);

                case 'news':
                    return app(NewsController::class)->detailNews($content['id']);

                case 'cate_product':
                    return app(ProductController::class)->categoryProduct($content['id']);

                case 'cate_news':
                    return app(NewsController::class)->categoryNews($content['id']);

                case 'page':
                    return app(PageController::class)->page($content['id']);

                default:
                    Log::warning('Unknown content type', [
                        'type' => $content['type'],
                        'id' => $content['id'],
                        'slug' => $content['slug'],
                    ]);

                    return view('errors.404');
            }
        } catch (\Exception $e) {
            Log::error('Error dispatching to controller', [
                'type' => $content['type'],
                'id' => $content['id'],
                'slug' => $content['slug'],
                'error' => $e->getMessage(),
            ]);

            return view('errors.404');
        }
    }
}
