<?php

namespace App\Repositories\Eloquent;

use App\Models\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;

class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    public function __construct(Menu $model)
    {
        parent::__construct($model);
    }

    public function getFilteredMenus(array $filters, $perPage = 10)
    {
        $query = $this->model->query();
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        $sortField = $filters['sort_field'] ?? 'stt';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        $query->orderBy($sortField, $sortDirection);
        return $query->paginate($perPage);
    }

    public function getActiveMenus()
    {
        return $this->model->where('status', 1)->orderBy('stt', 'asc')->get();
    }

    public function getMenuTree()
    {
        return $this->model->with('children')->where('parent_id', 0)->orderBy('stt', 'asc')->get();
    }
}
