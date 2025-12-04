<?php

namespace App\Repositories\Interfaces;

interface GalleryRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered galleries with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredGalleries(array $filters, $perPage = 10);

    /**
     * Get active galleries
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveGalleries();

    /**
     * Update numerical order of a gallery
     *
     * @param string $uuid
     * @param int $order
     * @return bool
     */
    public function updateOrder($uuid, $order);
}