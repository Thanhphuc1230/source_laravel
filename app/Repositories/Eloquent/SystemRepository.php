<?php

namespace App\Repositories\Eloquent;

use App\Models\System;
use App\Repositories\Interfaces\SystemRepositoryInterface;

class SystemRepository extends BaseRepository implements SystemRepositoryInterface
{
    public function __construct(System $model)
    {
        parent::__construct($model);
    }

    public function getSetting($key)
    {
        return $this->model->where('key', $key)->first();
    }

    public function updateSetting($key, $value)
    {
        return $this->model->where('key', $key)->update(['value' => $value]);
    }
}
