<?php

namespace App\Repositories\Eloquent;

use App\Models\CateNew;
use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Support\Str;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    /**
     * NewsRepository constructor.
     *
     * @param News $model
     */
    public function __construct(News $model)
    {
        parent::__construct($model);
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

        return $query->select('uuid', 'name_vn', 'slug', 'status', 'home', 'stt', 'created_at', 'category_id', 'image')
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

    /**
     * @inheritDoc
     */
    public function generateUniqueSlug($name, $uuid = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        // Check if slug exists
        $query = $this->model->where('slug', $slug);
        
        // Exclude current news when updating
        if ($uuid) {
            $query->where('uuid', '!=', $uuid);
        }
        
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = $this->model->where('slug', $slug);
            
            if ($uuid) {
                $query->where('uuid', '!=', $uuid);
            }
        }
        
        return $slug;
    }
}
