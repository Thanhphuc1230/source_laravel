<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchService
{
    /**
     * Search products based on query
     *
     * @param Request $request
     * @return array
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        $perPage = $this->getPerPageValue($request);

        $data = [];

        if (!empty($query)) {
            // Base query for search
            $searchQuery = Product::with(['cate:id_cate_product,name_vn,slug'])
                ->select('id_product', 'uuid', 'name_vn', 'slug', 'price', 'price_old', 'image', 'intro_vn', 'category_id', 'status', 'stt', 'created_at')
                ->where('status', 1);

            // Apply search filters
            $searchQuery = $this->applySearchFilters($searchQuery, $query);

            // Apply sorting (default by relevance, then by stt)
            $searchQuery = $this->applySearchSorting($searchQuery, $request);

            $data['products'] = $searchQuery->paginate($perPage)->appends($request->query());
        } else {
            $data['products'] = collect([]);
        }

        $data['search_query'] = $query;
        $data['total_results'] = $data['products']->total() ?? 0;

        return $data;
    }

    /**
     * Apply search filters to product query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchQuery
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applySearchFilters($query, string $searchQuery)
    {
        // Search only in Vietnamese product name
        $query->where('name_vn', 'like', "%{$searchQuery}%");

        return $query;
    }

    /**
     * Apply sorting to search results
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applySearchSorting($query, Request $request)
    {
        $sort = $request->get('sort', 'relevance');

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
            case 'relevance':
            default:
                // For relevance, order by status (active first), then by stt, then by created date
                $query->orderBy('status', 'desc')
                      ->orderBy('stt', 'asc')
                      ->orderBy('created_at', 'desc');
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
        $perPage = (int) $request->get('per_page', 16);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 16;
        }
        return $perPage;
    }

    /**
     * Handle complete search request
     *
     * @param Request $request
     * @return array
     */
    public function handleSearchRequest(Request $request)
    {
        // Validate search query
        $request->validate([
            'q' => 'nullable|string|max:255',
            'sort' => 'nullable|string|in:relevance,name_asc,name_desc,price_asc,price_desc,date_asc,date_desc',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $searchQuery = $request->get('q', '');

        // If no search query, return empty result
        if (empty(trim($searchQuery))) {
            return [
                'should_redirect' => true,
                'redirect_route' => 'web.home'
            ];
        }

        // Get search results
        $data = $this->searchProducts($request);

        // Add page metadata
        $data['page_title'] = 'Tìm kiếm: "' . $searchQuery . '"';
        $data['page_description'] = 'Kết quả tìm kiếm cho "' . $searchQuery . '" - ' . $data['total_results'] . ' sản phẩm';

        return $data;
    }

    /**
     * Get search suggestions for AJAX
     *
     * @param Request $request
     * @return array
     */
    public function getSearchSuggestionsForAjax(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return ['suggestions' => []];
        }

        // Cache search suggestions
        $suggestions = Product::where('status', 1)
            ->where('name_vn', 'like', "%{$query}%")
            ->orderBy('stt', 'asc')
            ->limit(10)
            ->pluck('name_vn')
            ->toArray();

        return ['suggestions' => $suggestions];
    }
}