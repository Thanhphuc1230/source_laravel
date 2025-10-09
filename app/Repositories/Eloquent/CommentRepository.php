<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    /**
     * Get filtered comments with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredComments(array $filters, $perPage = 10)
    {
        $query = $this->model->newQuery();

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get all active comments
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveComments()
    {
        return $this->model->active()
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    /**
     * Get all pending comments  
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingComments()
    {
        return $this->model->pending()
                          ->orderBy('created_at', 'desc')
                          ->get();
    }
}