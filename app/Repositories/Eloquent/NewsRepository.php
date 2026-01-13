<?php

namespace App\Repositories\Eloquent;

use App\Models\CateNew;
use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Services\SlugService;
use Illuminate\Support\Str;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    protected $slugService;

    /**
     * NewsRepository constructor.
     *
     * @param News $model
     * @param SlugService $slugService
     */
    public function __construct(News $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
    }

    /**
     * @inheritDoc
     */
    public function getFilteredNews(array $filters, $perPage = 10)
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

        return $query->select('uuid', 'name_vn', 'slug', 'status', 'home', 'stt', 'created_at', 'category_id', 'image', 'id_new')->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActiveCategories()
    {
        return CateNew::with('children')
            ->where('status', 1)
            ->where('parent_id', 0)
            ->get();
    }
}
