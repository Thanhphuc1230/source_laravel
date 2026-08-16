<?php

namespace App\Repositories\Eloquent;

use App\Models\About;
use App\Repositories\Interfaces\AboutRepositoryInterface;
use App\Services\SlugService;

class AboutRepository extends BaseRepository implements AboutRepositoryInterface
{
    protected $slugService;

    /**
     * AboutRepository constructor.
     *
     * @param About $model
     * @param SlugService $slugService
     */
    public function __construct(About $model, SlugService $slugService)
    {
        parent::__construct($model);
        $this->slugService = $slugService;
    }

    /**
     * @inheritDoc
     */
    public function getFilteredAbouts(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Apply search filter
        if (isset($filters['search']) && !empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name_en', 'LIKE', "%{$searchTerm}%");
            });
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
    public function getActiveAbouts()
    {
        return $this->model->where('status', 1)
            ->orderBy('stt', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getAboutBySlug($slug)
    {
        return $this->model->where(function ($q) use ($slug) {
            $q->where('slug_vn', $slug)->orWhere('slug_en', $slug);
        })
            ->where('status', 1)
            ->first();
    }
}
