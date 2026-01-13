<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Get the underlying Eloquent model instance
     *
     * @return Model
     */
    public function getModelInstance()
    {
        return $this->model;
    }

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * @inheritDoc
     */
    public function paginate($perPage = 10, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @inheritDoc
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @inheritDoc
     */
    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->model->where($field, $value)->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function findByUuid($uuid, $columns = ['*'])
    {
        return $this->model->where('uuid', $uuid)->first($columns);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        $data = $this->prepareDataForCreate($data);
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(array $data, $id)
    {
        // if $id is a uuid (string format) try to find by uuid, otherwise by primary key
        $record = $this->isUuid($id) ? $this->findByUuid($id) : $this->find($id);

        if (! $record) {
            // nothing to update
            return false;
        }

        $data = $this->prepareDataForUpdate($data, $record);
        return $record->update($data);
    }
    
    /**
     * Prepare data for create operation
     * 
     * @param array $data
     * @return array
     */
    protected function prepareDataForCreate(array $data)
    {
        // Handle timestamps
        $data = $this->handleTimestamps($data);
        
        // Handle UUID
        $data = $this->handleUuid($data);
        
        // Handle default status if applicable
        if (!isset($data['status']) && in_array('status', $this->model->getFillable())) {
            $data['status'] = 1;
        }
        
        return $data;
    }
    
    /**
     * Prepare data for update operation
     * 
     * @param array $data
     * @param Model $record
     * @return array
     */
    protected function prepareDataForUpdate(array $data, $record)
    {
        // Handle timestamps
        $data = $this->handleTimestamps($data, $record);
        
        return $data;
    }
    
    /**
     * Handle timestamps for create/update operations
     *
     * @param array $data
     * @param \Illuminate\Database\Eloquent\Model|null $record Existing record for updates
     * @return array
     */
    protected function handleTimestamps(array $data, $record = null)
    {
        // Add created_at timestamp for new records or preserve provided value
        if (!isset($data['created_at']) && !$record) {
            $data['created_at'] = now();
        } elseif ($record && isset($data['created_at'])) {
            // For updates, resolve the created_at date
            $data['created_at'] = $this->resolveCreatedAt($data['created_at'], $record->created_at ?? null);
        }
        
        // Always set updated_at on updates
        if ($record) {
            $data['updated_at'] = now();
        }
        
        return $data;
    }
    
    /**
     * Resolve created_at date format
     *
     * @param string|null $createdAt
     * @param string|null $existingCreatedAt
     * @return \DateTime|string|null
     */
    protected function resolveCreatedAt($createdAt = null, $existingCreatedAt = null)
    {
        if (empty($createdAt)) {
            return $existingCreatedAt ?? now();
        }

        try {
            return new \DateTime($createdAt);
        } catch (\Exception $e) {
            return now();
        }
    }
    
    /**
     * Handle UUID for create operations
     *
     * @param array $data
     * @return array
     */
    protected function handleUuid(array $data)
    {
        // Add UUID if not already set
        if (!isset($data['uuid'])) {
            $data['uuid'] = \Illuminate\Support\Str::uuid();
        }
        
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        // support deleting by uuid or by primary id
        if ($this->isUuid($id)) {
            return (bool) $this->model->where('uuid', $id)->delete();
        }

        return $this->model->destroy($id);
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple(array $ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * @inheritDoc
     */
    public function getFilteredPaginate(array $filters, $perPage = 10, $columns = ['*'])
    {
        $query = $this->model->query();

        // Apply search filter
        if (isset($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        // Apply sorting
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage, $columns);
    }

    /**
     * Update status of a resource
     *
     * @param string $uuid
     * @param int $status
     * @return bool
     */
    public function updateStatus($uuid, $status)
    {
        $model = $this->model->where('uuid', $uuid)->first();
        if ($model) {
            $model->status = $status;
            return $model->save();
        }
        return false;
    }

    /**
     * Update numerical order of a resource
     * 
     * @param string $uuid
     * @param int $order
     * @return bool
     */
    public function updateOrder($uuid, $order)
    {
        return $this->model->where('uuid', $uuid)->update(['stt' => $order]);
    }

    /**
     * Delete resources by UUIDs
     *
     * @param array $uuids
     * @return bool
     */
    public function deleteByUuids(array $uuids)
    {
        return $this->model->whereIn('uuid', $uuids)->delete();
    }

    /**
     * Find all resources by UUIDs
     *
     * @param array $uuids
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByUuids(array $uuids, $columns = ['*'])
    {
        return $this->model->whereIn('uuid', $uuids)->get($columns);
    }

    /**
     * Determine if a given value looks like a UUID.
     *
     * @param mixed $value
     * @return bool
     */
    protected function isUuid($value)
    {
        if (! is_string($value)) {
            return false;
        }

        // simple UUID v4 pattern check
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value);
    }

    /**
     * Generate unique slug for a model
     * 
     * @param string $name The name to generate slug from
     * @param string|null $uuid UUID of existing record (for updates)
     * @param string $tableName Table name for slug uniqueness check
     * @param string $slugField Field name for slug (default 'slug')
     * @param string $primaryKey Primary key field name
     * @return string
     */
    public function generateUniqueSlug(string $name, ?string $uuid = null, string $tableName = null, string $slugField = 'slug', string $primaryKey = null)
    {
        // Use slugService if available in child repository
        if (property_exists($this, 'slugService') && $this->slugService) {
            $tableName = $tableName ?? $this->model->getTable();
            $primaryKey = $primaryKey ?? $this->model->getKeyName();
            
            $id = null;
            if ($uuid) {
                $record = $this->model->where('uuid', $uuid)->first();
                $id = $record ? $record->{$primaryKey} : null;
            }

            return !$id 
                ? $this->slugService->generateUniqueSlugWithId($name, 0, $tableName, $slugField)
                : $this->slugService->generateUniqueSlugWithIdGlobal($name, $id, $tableName, $slugField);
        }

        // Fallback: simple slug generation without SlugService
        $slug = \Illuminate\Support\Str::slug($name);
        $count = 1;
        $originalSlug = $slug;
        
        while (true) {
            $query = $this->model->where($slugField, $slug);
            if ($uuid) {
                $query->where('uuid', '!=', $uuid);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }

    /**
     * Create record with automatic slug generation
     * 
     * @param array $data
     * @param string $nameField Field name to generate slug from (default 'name_vn')
     * @param string $slugField Field name for slug (default 'slug')
     * @return Model
     */
    public function createWithAutoSlug(array $data, string $nameField = 'name_vn', string $slugField = 'slug')
    {
        $hasCustomSlug = !empty($data[$slugField]);
        
        if (!$hasCustomSlug) {
            $tableName = $this->model->getTable();
            $data[$slugField] = $this->generateUniqueSlug($data[$nameField], null, $tableName, $slugField);
        }
        
        $record = $this->create($data);
        
        // Regenerate slug with actual ID if needed (for SlugService)
        if (!$hasCustomSlug && property_exists($this, 'slugService') && $this->slugService) {
            $realSlug = $this->generateUniqueSlug($data[$nameField], $record->uuid, $this->model->getTable(), $slugField);
            if ($realSlug !== $record->{$slugField}) {
                $this->update([$slugField => $realSlug], $record->uuid);
                $record->{$slugField} = $realSlug;
            }
        }
        
        return $record;
    }
}
