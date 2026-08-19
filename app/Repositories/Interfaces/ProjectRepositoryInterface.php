<?php

namespace App\Repositories\Interfaces;

interface ProjectRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered projects with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredProjects(array $filters, $perPage = 10);

    /**
     * Get active categories for projects
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories();
}
