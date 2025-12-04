<?php

namespace App\Repositories\Eloquent;

use App\Models\Gallery;
use App\Repositories\Interfaces\GalleryRepositoryInterface;

class GalleryRepository extends BaseRepository implements GalleryRepositoryInterface
{
    /**
     * GalleryRepository constructor.
     *
     * @param Gallery $model
     */
    public function __construct(Gallery $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredGalleries(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Apply search filter
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // Apply type filter if the field exists in your model
        if (isset($filters['type']) && $filters['type'] !== '' && $this->hasColumn('type')) {
            $query->where('type', $filters['type']);
        }

        // Apply sorting
        $sortField = $filters['sort_field'] ?? 'stt';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function getActiveGalleries()
    {
        return $this->model->where('status', 1)
            ->orderBy('stt', 'asc')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function updateOrder($uuid, $order)
    {
        return $this->model->where('uuid', $uuid)->update(['stt' => $order]);
    }

    /**
     * Check if a column exists in the model's table
     *
     * @param string $column
     * @return bool
     */
    protected function hasColumn($column)
    {
        return \Illuminate\Support\Facades\Schema::hasColumn($this->model->getTable(), $column);
    }
}