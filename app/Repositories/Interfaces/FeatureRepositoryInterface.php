<?php

namespace App\Repositories\Interfaces;

interface FeatureRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered features with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredFeatures(array $filters, $perPage = 10);

    /**
     * Get active features
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveFeatures();

    /**
     * Update numerical order of a feature
     *
     * @param string $uuid
     * @param int $order
     * @return bool
     */
    public function updateOrder($uuid, $order);
}