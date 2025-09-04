<?php

namespace App\Repositories\Interfaces;

interface MenuRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered menus with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredMenus(array $filters, $perPage = 10);

    /**
     * Get all active menus
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveMenus();

    /**
     * Get menu tree for display
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenuTree();
}
