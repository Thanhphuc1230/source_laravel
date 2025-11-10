<?php

namespace App\Repositories\Interfaces;

interface CommentRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered comments with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredComments(array $filters, $perPage = 10);

    /**
     * Get approved comments for frontend display
     *
     * @param string $type
     * @param int $itemId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getApprovedCommentsForItem($type, $itemId);

    /**
     * Create a new comment
     *
     * @param array $data
     * @return \App\Models\Comment
     */
    public function createComment(array $data);
}