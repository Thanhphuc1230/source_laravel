<?php

namespace App\Repositories\Interfaces;

interface CateNewRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered news categories with pagination
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
     * Generate unique slug for category
     *
     * @param string $name
     * @param string|null $uuid
     * @return string
     */
    public function generateUniqueSlug($name, $uuid = null);

    /**
     * Get categories with children for hierarchical display
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesWithChildren();
}
