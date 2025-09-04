<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get all resources
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*']);

    /**
     * Get paginated resources
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 10, $columns = ['*']);

    /**
     * Find resource by id
     *
     * @param string $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $columns = ['*']);

    /**
     * Find resource by field
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findBy($field, $value, $columns = ['*']);

    /**
     * Find resource by UUID
     *
     * @param string $uuid
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByUuid($uuid, $columns = ['*']);
    
    /**
     * Find all resources by UUIDs
     *
     * @param array $uuids
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByUuids(array $uuids, $columns = ['*']);

    /**
     * Create a new resource
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Update a resource
     *
     * @param array $data
     * @param string $id
     * @return bool
     */
    public function update(array $data, $id);

    /**
     * Delete a resource
     *
     * @param string $id
     * @return bool
     */
    public function delete($id);

    /**
     * Delete multiple resources
     *
     * @param array $ids
     * @return bool
     */
    public function deleteMultiple(array $ids);

    /**
     * Get filtered and paginated resources
     *
     * @param array $filters
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredPaginate(array $filters, $perPage = 10, $columns = ['*']);

    /**
     * Get the underlying Eloquent model instance
     *
     * @return Model
     */
    public function getModelInstance();
}
