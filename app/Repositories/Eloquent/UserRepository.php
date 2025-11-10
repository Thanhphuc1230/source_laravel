<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredUsers(array $filters, $perPage = 10)
    {
        $query = $this->model->query();

        // Search filter
        if (isset($filters['search']) && !empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('fullname', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('username', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Level filter
        if (isset($filters['level']) && $filters['level'] !== '') {
            $query->where('level', $filters['level']);
        }

        // Status filter
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        // Sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function createUser(array $data)
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Create user
        $user = $this->create($data);

        // Assign roles if provided
        if (isset($data['roles']) && is_array($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function updateUser(array $data, $id)
    {
        // Hash password if provided and not empty
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Remove password from data if empty
            unset($data['password']);
        }

        // Update user
        $result = $this->update($data, $id);

        if ($result) {
            $user = $this->find($id);

            // Update roles if provided
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->syncRoles($data['roles']);
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getUserWithRolesByUuid($uuid)
    {
        return $this->model->with('roles.permissions')->where('uuid', $uuid)->first();
    }

    /**
     * @inheritDoc
     */
    public function getAllRoles()
    {
        return Role::orderBy('display_name')->get();
    }

    /**
     * @inheritDoc
     */
    public function deleteByUuids(array $uuids)
    {
        return $this->model->whereIn('uuid', $uuids)->delete();
    }

    /**
     * Override prepareDataForCreate to handle user-specific logic
     */
    protected function prepareDataForCreate(array $data)
    {
        $data = parent::prepareDataForCreate($data);

        // Set default level if not provided
        if (!isset($data['level'])) {
            $data['level'] = 3; // Default to user level
        }

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = true;
        }

        return $data;
    }
}