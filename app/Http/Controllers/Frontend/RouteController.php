<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\SlugResolutionService;

class RouteController extends Controller
{
    protected $slugResolutionService;

    public function __construct(SlugResolutionService $slugResolutionService)
    {
        $this->slugResolutionService = $slugResolutionService;
    }

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
        $result = CacheService::remember(
            CacheService::TAGS['frontend'] ?? 'frontend',
            $cacheKey,
            $cacheTtl,
            function () use ($slug) {
                return $this->slugResolutionService->findContentBySlug($slug);
            }
        );

        if (! $result) {
            return view('errors.404');
        }

        return $this->dispatchToController($result);
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
                    return view('errors.404');
            }
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }
}
