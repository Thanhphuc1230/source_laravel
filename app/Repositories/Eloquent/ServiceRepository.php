<?php

namespace App\Repositories\Eloquent;

use App\Models\CateService;
use App\Models\Service;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Services\SlugService;

class ServiceRepository extends BaseRepository implements ServiceRepositoryInterface
{
    protected $slugService;

    /**
     * ServiceRepository constructor.
     *
     * @param Service $model
     * @param SlugService $slugService
     */
    public function __construct(Service $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
    }

    /**
     * @inheritDoc
     */
    public function getFilteredServices(array $filters, $perPage = 10)
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
            $query->where('category_id', $categoryId);
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->with('cate')->select('uuid', 'name_vn', 'name_en', 'slug_vn', 'slug_en', 'status', 'home', 'stt', 'created_at', 'category_id', 'image_vn', 'image_en', 'id_service')->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActiveCategories()
    {
        return CateService::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();
    }
}
