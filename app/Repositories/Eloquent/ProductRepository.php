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
                $q->where('parent_id', $categoryId)
                    ->orWhere('id_category_product', $categoryId);
            });
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->select('uuid', 'name_vn', 'slug', 'status', 'home','hot', 'stt', 'created_at', 'category_id', 'image', 'id_product')->orderBy('created_at', 'desc')
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

    public function generateUniqueSlug($name, $uuid = null)
    {
        $id = null;
        if ($uuid) {
            $product = $this->model->where('uuid', $uuid)->first();
            $id = $product ? $product->id_product : null;
        }

        return !$id 
            ? $this->slugService->generateUniqueSlugWithId($name, 0, 'tp_products')
            : $this->slugService->generateUniqueSlugWithIdGlobal($name, $id, 'tp_products');
    }

    public function createWithAutoSlug(array $data, string $nameField = 'name_vn')
    {
        $hasCustomSlug = !empty($data['slug']);
        
        if (!$hasCustomSlug) {
            $data['slug'] = $this->slugService->generateUniqueSlugWithId($data[$nameField], 0, 'tp_products');
        }
        
        $product = $this->create($data);
        
        if (!$hasCustomSlug) {
            $realSlug = $this->generateUniqueSlug($data[$nameField], $product->uuid);
            $this->update(['slug' => $realSlug], $product->uuid);
            $product->slug = $realSlug;
        }
        
        return $product;
    }
}
