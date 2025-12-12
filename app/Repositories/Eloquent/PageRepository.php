<?php

namespace App\Repositories\Eloquent;

use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Services\SlugService;
use Illuminate\Support\Str;

class PageRepository extends BaseRepository implements PageRepositoryInterface
{
    protected $slugService;

    /**
     * PageRepository constructor.
     *
     * @param Page $model
     * @param SlugService $slugService
     */
    public function __construct(Page $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
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
    
    public function generateUniqueSlug($name, $uuid = null)
    {
        $id = null;
        if ($uuid) {
            $page = $this->model->where('uuid', $uuid)->first();
            $id = $page ? $page->id_page : null;
        }

        return !$id 
            ? $this->slugService->generateUniqueSlugWithId($name, 0, 'tp_pages')
            : $this->slugService->generateUniqueSlugWithIdGlobal($name, $id, 'tp_pages');
    }

    public function createWithAutoSlug(array $data, string $nameField = 'name_vn')
    {
        $hasCustomSlug = !empty($data['slug']);
        
        if (!$hasCustomSlug) {
            $data['slug'] = $this->slugService->generateUniqueSlugWithId($data[$nameField], 0, 'tp_pages');
        }
        
        $page = $this->create($data);
        
        if (!$hasCustomSlug) {
            $realSlug = $this->generateUniqueSlug($data[$nameField], $page->uuid);
            $this->update(['slug' => $realSlug], $page->uuid);
            $page->slug = $realSlug;
        }
        
        return $page;
    }
}
