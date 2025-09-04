<?php

namespace App\Repositories\Interfaces;

interface ContactRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered contacts with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredContacts(array $filters, $perPage = 10);

    /**
     * Get all active contacts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveContacts();
}
