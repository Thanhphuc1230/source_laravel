<?php

namespace App\Repositories\Eloquent;

use App\Models\CateNew;
use App\Repositories\Interfaces\CateNewRepositoryInterface;
use App\Services\SlugService;
use Illuminate\Support\Str;

class CateNewRepository extends BaseRepository implements CateNewRepositoryInterface
{
    protected $slugService;

    /**
     * CateNewRepository constructor.
     *
     * @param CateNew $model
     * @param SlugService $slugService
     */
    public function __construct(CateNew $model, SlugService $slugService)
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

        return $query->select('uuid', 'name_vn', 'slug', 'status', 'parent_id', 'stt', 'image','created_at', 'id_cate_new')->orderBy('created_at', 'desc')
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

    public function generateUniqueSlug($name, $uuid = null)
    {
        $id = null;
        if ($uuid) {
            $category = $this->model->where('uuid', $uuid)->first();
            $id = $category ? $category->id_cate_new : null;
        }

        return !$id 
            ? $this->slugService->generateUniqueSlugWithId($name, 0, 'tp_cate_news')
            : $this->slugService->generateUniqueSlugWithIdGlobal($name, $id, 'tp_cate_news');
    }

    public function createWithAutoSlug(array $data, string $nameField = 'name_vn')
    {
        $hasCustomSlug = !empty($data['slug']);
        
        if (!$hasCustomSlug) {
            $data['slug'] = $this->slugService->generateUniqueSlugWithId($data[$nameField], 0, 'tp_cate_news');
        }
        
        $category = $this->create($data);
        
        if (!$hasCustomSlug) {
            $realSlug = $this->generateUniqueSlug($data[$nameField], $category->uuid);
            $this->update(['slug' => $realSlug], $category->uuid);
            $category->slug = $realSlug;
        }
        
        return $category;
    }

    public function getCategoriesWithChildren()
    {
        return $this->model->with('children')
            ->where('parent_id', 0)
            ->orderBy('stt', 'asc')
            ->get();
    }
}
