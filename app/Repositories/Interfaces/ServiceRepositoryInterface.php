<?php

namespace App\Repositories\Interfaces;

interface ServiceRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered services with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredServices(array $filters, $perPage = 10);

    /**
     * Get active categories for services
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories();
}
