<?php

namespace App\Repositories\Eloquent;

use App\Models\SiteSetting;
use App\Repositories\Interfaces\SiteSettingRepositoryInterface;

class SiteSettingRepository extends BaseRepository implements SiteSettingRepositoryInterface
{
    /**
     * SiteSettingRepository constructor.
     *
     * @param SiteSetting $model
     */
    public function __construct(SiteSetting $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function getByKey($key)
    {
        return $this->model->where('key', $key)->first();
    }

    /**
     * @inheritDoc
     */
    public function getByGroup($group)
    {
        return $this->model->where('group', $group)->get();
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}