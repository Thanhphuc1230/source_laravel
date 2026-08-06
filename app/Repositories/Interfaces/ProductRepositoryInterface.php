<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered products with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredProducts(array $filters, $perPage = 10);

    /**
     * Get active categories for products
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories();

    /**
     * Get active brands for products
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveBrands();
}
