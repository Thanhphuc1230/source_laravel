<?php

namespace App\Repositories\Eloquent;

use App\Models\CateProduct;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Services\SlugService;
use Illuminate\Support\Str;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $slugService;

    /**
     * ProductRepository constructor.
     *
     * @param Product $model
     * @param SlugService $slugService
     */
    public function __construct(Product $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
    }

    /**
     * @inheritDoc
     */
    public function getFilteredProducts(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Search filter
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // Category filter
        if (isset($filters['category']) && $filters['category'] != 0) {
            $categoryId = $filters['category'];
            $query->where(function ($q) use ($categoryId) {
                $q->where('id_cate_product', $categoryId);
            });
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->with('cate')->select('uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'status', 'home', 'hot', 'stt', 'created_at', 'category_id', 'image_vn', 'image_en', 'id_product')->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActiveCategories()
    {
        return CateProduct::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getActiveBrands()
    {
        return \App\Models\Brand::where('status', 1)
            ->orderBy('stt', 'asc')
            ->get();
    }
}
