<?php

namespace App\Repositories\Interfaces;

interface AboutRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered about entries with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredAbouts(array $filters, $perPage = 10);

    /**
     * Get active about entries
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveAbouts();

    /**
     * Get about entry by slug
     *
     * @param string $slug
     * @return \App\Models\About|null
     */
    public function getAboutBySlug($slug);
}
