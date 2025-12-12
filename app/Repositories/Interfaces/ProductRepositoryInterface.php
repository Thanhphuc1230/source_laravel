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
     * Generate unique slug for product
     *
     * @param string $name
     * @param string|null $uuid
     * @return string
     */
    public function generateUniqueSlug($name, $uuid = null);

    /**
     * Create product with auto-generated slug
     *
     * @param array $data
     * @param string $nameField
     * @return mixed
     */
    public function createWithAutoSlug(array $data, string $nameField = 'name_vn');
}
