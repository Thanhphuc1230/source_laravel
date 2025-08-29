<?php

namespace App\Repositories\Eloquent;

use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Support\Str;

class PageRepository extends BaseRepository implements PageRepositoryInterface
{
    /**
     * PageRepository constructor.
     *
     * @param Page $model
     */
    public function __construct(Page $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredPages(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Apply search filter
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name_en', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // Apply parent filter
        if (isset($filters['parent_id']) && $filters['parent_id'] !== '') {
            $query->where('parent_id', $filters['parent_id']);
        }

        // Apply footer filter
        if (isset($filters['footer']) && $filters['footer'] !== '') {
            $query->where('footer', $filters['footer']);
        }

        // Apply sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActivePages()
    {
        return $this->model->where('status', 1)
            ->orderBy('stt', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getPageBySlug($slug)
    {
        return $this->model->where('slug', $slug)
            ->where('status', 1)
            ->first();
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
        
        // Exclude current page when updating
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
