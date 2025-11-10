<?php

namespace App\Repositories\Eloquent;

use App\Models\Feature;
use App\Repositories\Interfaces\FeatureRepositoryInterface;

class FeatureRepository extends BaseRepository implements FeatureRepositoryInterface
{
    /**
     * FeatureRepository constructor.
     *
     * @param Feature $model
     */
    public function __construct(Feature $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredFeatures(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Apply search filter
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title_vn', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content_vn', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
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
    public function getActiveFeatures()
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
}