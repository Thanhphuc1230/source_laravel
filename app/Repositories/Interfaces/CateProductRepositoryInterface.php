<?php

namespace App\Repositories\Interfaces;

interface CateProductRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered categories with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredCategories(array $filters, $perPage = 10);

    /**
     * Get active parent categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveParentCategories();

    /**
     * Get categories with children for hierarchical display
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesWithChildren();
}
