<?php

namespace App\Repositories\Interfaces;

interface SiteSettingRepositoryInterface extends RepositoryInterface
{
    /**
     * Get setting by key
     *
     * @param string $key
     * @return \App\Models\SiteSetting|null
     */
    public function getByKey($key);

    /**
     * Get settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByGroup($group);

    /**
     * Update or create setting
     *
     * @param array $attributes
     * @param array $values
     * @return \App\Models\SiteSetting
     */
    public function updateOrCreate(array $attributes, array $values = []);
}