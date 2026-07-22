<?php

namespace App\Services;

use App\Models\CateProduct;
use App\Models\Product;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductService
{
    protected $productRepository;
    protected $commentRepository;

    public function __construct(ProductRepositoryInterface $productRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->productRepository = $productRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Get data for product category page
     *
     * @param int $id_cate_product
     * @param Request $request
     * @return array
     */
    public function getCategoryProductData($id_cate_product, Request $request)
    {
        $data = [];

        // Cache category data
        $data['category_detail'] = CateProduct::where('status', 1)->where('id_cate_product', $id_cate_product)->firstOrFail();

        // Cache category list (sidebar) - Load all categories with hierarchy
        $data['category_product'] = CateProduct::with(['products', 'children.products'])
            ->where('status', 1)
            ->orderBy('stt', 'asc')
            ->get()
            ->groupBy('parent_id');

        // Build category id list (include children recursively)
        $categoryIds = $this->getAllCategoryIds($data['category_detail']->id_cate_product);

        // Base query
        $query = Product::with(['cate:id_cate_product,name_vn,name_en,slug_vn,slug_en'])
            ->select('id_product', 'uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'price', 'price_old', 'image_vn', 'image_en', 'intro_vn', 'intro_en', 'category_id', 'status', 'stt', 'created_at')
            ->whereIn('category_id', $categoryIds)
            ->where('status', 1)
            ->orderBy('created_at', 'desc');

        // Apply filters
        $query = $this->applyProductFilters($query, $request);

        // Apply sorting
        $query = $this->applyProductSorting($query, $request);

        // Get per-page value
        $perPage = $this->getPerPageValue($request);

        $data['products'] = $query->paginate($perPage)->appends($request->query());

        return $data;
    }

    /**
     * Get data for product detail page
     *
     * @param int $id_product
     * @return array
     */
    public function getDetailProductData($id_product)
    {
        $data = [];

        // Cache product detail
        $data['product_detail'] = Product::with(['cate:id_cate_product,name_vn,name_en,slug_vn,slug_en'])
            ->where('id_product', $id_product)
            ->firstOrFail();

        // Cache related products
        $data['related_product'] = Product::where('category_id', $data['product_detail']->category_id)
            ->where('id_product', '!=', $data['product_detail']->id_product)
            ->where('status', 1)
            ->orderBy('stt', 'asc')
            ->limit(8)
            ->get();

        // Cache approved comments
        $data['comments'] = $this->commentRepository->getApprovedCommentsForItem('product', $data['product_detail']->id_product);

        return $data;
    }

    /**
     * Apply filters to product query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyProductFilters($query, Request $request)
    {
        // Name search filter
        if ($request->filled('name')) {
            $name = $request->get('name');
            $query->where(function ($q) use ($name) {
                $q->where('name_vn', 'like', "%{$name}%")
                  ->orWhere('name_en', 'like', "%{$name}%");
            });
        }

        // Price filters
        if ($request->filled('price_min')) {
            $priceMin = (float) $request->get('price_min');
            $query->where('price', '>=', $priceMin);
        }

        if ($request->filled('price_max')) {
            $priceMax = (float) $request->get('price_max');
            $query->where('price', '<=', $priceMax);
        }

        // Date filters
        if ($request->filled('date_from')) {
            $dateFrom = $request->get('date_from');
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($request->filled('date_to')) {
            $dateTo = $request->get('date_to');
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query;
    }

    /**
     * Apply sorting to product query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyProductSorting($query, Request $request)
    {
        $sort = $request->get('sort');

        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name_vn', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name_vn', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('stt', 'asc');
                break;
        }

        return $query;
    }

    /**
     * Get per-page value with validation
     *
     * @param Request $request
     * @return int
     */
    private function getPerPageValue(Request $request)
    {
        $defaultPerPage = (int) \App\Models\ProductSetting::get('products_pagination', 8);
        $perPage = (int) $request->get('per_page', $defaultPerPage);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = $defaultPerPage;
        }
        return $perPage;
    }

    /**
     * Get all category IDs recursively (parent + all children)
     *
     * @param int $parentId
     * @return array
     */
    private function getAllCategoryIds($parentId)
    {
        $categoryIds = [$parentId];

        $this->getChildCategoryIds($parentId, $categoryIds);

        return $categoryIds;
    }

    /**
     * Recursively get all child category IDs
     *
     * @param int $parentId
     * @param array &$categoryIds
     * @return void
     */
    private function getChildCategoryIds($parentId, &$categoryIds)
    {
        $children = CateProduct::where('parent_id', $parentId)->pluck('id_cate_product')->toArray();

        foreach ($children as $childId) {
            $categoryIds[] = $childId;
            $this->getChildCategoryIds($childId, $categoryIds);
        }
    }
}