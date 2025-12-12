<?php

namespace App\Repositories\Interfaces;

interface PageRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered pages with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredPages(array $filters, $perPage = 10);

    /**
     * Get active pages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivePages();
    
    /**
     * Get page by slug
     *
     * @param string $slug
     * @return \App\Models\Page|null
     */
    public function getPageBySlug($slug);
    
    /**
     * Generate unique slug for page
     *
     * @param string $name
     * @param string|null $uuid
     * @return string
     */
    public function generateUniqueSlug($name, $uuid = null);

    public function createWithAutoSlug(array $data, string $nameField = 'name_vn');
}
