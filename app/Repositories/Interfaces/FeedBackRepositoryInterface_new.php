<?php

namespace App\Repositories\Interfaces;

interface FeedbackRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered feedback with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredFeedback(array $filters, $perPage = 10);

    /**
     * Get all active feedback
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveFeedback();

    /**
     * Update feedback status
     *
     * @param string $uuid
     * @param int $status
     * @return bool
     */
    public function updateStatus($uuid, $status);
}
