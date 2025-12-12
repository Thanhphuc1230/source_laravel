<?php

namespace App\Repositories\Interfaces;

interface NewsRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered news with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredNews(array $filters, $perPage = 10);

    /**
     * Get active categories for news
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories();

    /**
     * Generate unique slug for news
     *
     * @param string $name
     * @param string|null $uuid
     * @return string
     */
    public function generateUniqueSlug($name, $uuid = null);

    public function createWithAutoSlug(array $data, string $nameField = 'name_vn');
}
