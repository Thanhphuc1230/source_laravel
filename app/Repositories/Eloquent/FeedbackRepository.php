<?php

namespace App\Repositories\Eloquent;

use App\Models\Feedback;
use App\Repositories\Interfaces\FeedbackRepositoryInterface;

class FeedbackRepository extends BaseRepository implements FeedbackRepositoryInterface
{
    /**
     * FeedbackRepository constructor.
     *
     * @param Feedback $model
     */
    public function __construct(Feedback $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredFeedback(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Apply search filter
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('message', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // Apply status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
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
    public function getActiveFeedback()
    {
        return $this->model->where('status', 1)
            ->orderBy('stt', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function updateStatus($uuid, $status)
    {
        return $this->model->where('uuid', $uuid)->update(['status' => $status]);
    }
}
