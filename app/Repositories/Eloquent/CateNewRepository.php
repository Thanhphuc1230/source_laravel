<?php

namespace App\Repositories\Eloquent;

use App\Models\CateNew;
use App\Repositories\Interfaces\CateNewRepositoryInterface;
use Illuminate\Support\Str;

class CateNewRepository extends BaseRepository implements CateNewRepositoryInterface
{
    /**
     * CateNewRepository constructor.
     *
     * @param CateNew $model
     */
    public function __construct(CateNew $model)
    {
        parent::__construct($model);
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

        return $query->select('uuid', 'name_vn', 'slug', 'status', 'parent_id', 'stt', 'image')
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
        
        // Exclude current category when updating
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

    /**
     * @inheritDoc
     */
    public function getCategoriesWithChildren()
    {
        return $this->model->with('children')
            ->where('parent_id', 0)
            ->orderBy('stt', 'asc')
            ->get();
    }
}
