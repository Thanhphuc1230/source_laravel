<?php

namespace App\Repositories\Interfaces;

interface SliderRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered sliders with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredSliders(array $filters, $perPage = 10);

    /**
     * Get active sliders
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveSliders();
    
    /**
     * Get sliders by type
     *
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSlidersByType($type);
    
    /**
     * Update numerical order of a slider
     *
     * @param string $uuid
     * @param int $order
     * @return bool
     */
    public function updateOrder($uuid, $order);
}
