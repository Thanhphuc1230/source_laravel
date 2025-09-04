<?php

namespace App\Repositories\Interfaces;

interface SystemRepositoryInterface extends RepositoryInterface
{
    /**
     * Get system settings by key
     *
     * @param string $key
     * @return mixed
     */
    public function getSetting($key);

    /**
     * Update system setting by key
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function updateSetting($key, $value);
}
