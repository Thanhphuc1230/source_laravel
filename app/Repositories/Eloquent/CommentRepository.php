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

        // Type filter
        if (!empty($filters['type'])) {
            // Convert type string to type_post number for filtering
            $typePost = $filters['type'] === 'news' ? 1 : ($filters['type'] === 'product' ? 2 : 3);
            $query->where('type_post', $typePost);
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get approved comments for frontend display
     *
     * @param string $type
     * @param int $itemId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getApprovedCommentsForItem($type, $itemId)
    {
        // Convert type string to type_post number
        $typePost = $type === 'news' ? 1 : 2;

        return $this->model->approved()
                          ->byItem($typePost, $itemId)
                          ->orderBy('created_at', 'desc')
                          ->get();
    }

    /**
     * Create a new comment
     *
     * @param array $data
     * @return Comment
     */
    public function createComment(array $data)
    {
        return $this->model->create($data);
    }
}