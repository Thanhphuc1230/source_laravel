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
     * Get all active comments
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveComments();

    /**
     * Get all pending comments
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingComments();
}