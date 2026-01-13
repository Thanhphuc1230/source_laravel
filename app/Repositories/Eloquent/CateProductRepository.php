<?php

namespace App\Repositories\Eloquent;

use App\Models\CateProduct;
use App\Repositories\Interfaces\CateProductRepositoryInterface;
use App\Services\SlugService;
use Illuminate\Support\Str;

class CateProductRepository extends BaseRepository implements CateProductRepositoryInterface
{
    protected $slugService;

    /**
     * CateProductRepository constructor.
     *
     * @param CateProduct $model
     * @param SlugService $slugService
     */
    public function __construct(CateProduct $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
    }

    /**
     * @inheritDoc
     */
    public function getFilteredCategories(array $filters, $perPage = 10)
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

        // Parent filter
        if (isset($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'stt';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return $query->select('uuid', 'name_vn', 'slug', 'status','home','parent_id', 'stt', 'created_at', 'id_cate_product')->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActiveParentCategories()
    {
        return $this->model->where('status', 1)
            ->where('parent_id', 0)
            ->orderBy('stt', 'asc')
            ->get();
    }

    public function getCategoriesWithChildren()
    {
        return $this->model->with('children')
            ->where('parent_id', 0)
            ->orderBy('stt', 'asc')
            ->get();
    }
}
