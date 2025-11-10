<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Get filtered users with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFilteredUsers(array $filters, $perPage = 10);

    /**
     * Create user with role assignment
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function createUser(array $data);

    /**
     * Update user with role assignment
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateUser(array $data, $id);

    /**
     * Get user with roles and permissions by UUID
     *
     * @param string $uuid
     * @return \App\Models\User|null
     */
    public function getUserWithRolesByUuid($uuid);

    /**
     * Get all roles for assignment
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRoles();

    /**
     * Delete multiple users by UUIDs
     *
     * @param array $uuids
     * @return bool
     */
    public function deleteByUuids(array $uuids);
}