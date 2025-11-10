<?php

namespace App\Repositories\Interfaces;

interface BrandRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered brands with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredBrands(array $filters, $perPage = 10);

    /**
     * Get active brands
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveBrands();

    /**
     * Update numerical order of a brand
     *
     * @param string $uuid
     * @param int $order
     * @return bool
     */
    public function updateOrder($uuid, $order);
}