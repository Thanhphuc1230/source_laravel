<?php

namespace App\Services;

use App\Repositories\Interfaces\SiteSettingRepositoryInterface;

class SiteSettingService
{
    protected $siteSettingRepository;

    public function __construct(SiteSettingRepositoryInterface $siteSettingRepository)
    {
        $this->siteSettingRepository = $siteSettingRepository;
    }

    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getValue($key, $default = null)
    {
        $setting = $this->siteSettingRepository->getByKey($key);
        return $setting ? $setting->value : $default;
    }

    /**
     * Get settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByGroup($group)
    {
        return $this->siteSettingRepository->getByGroup($group);
    }

    /**
     * Update or create setting
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $group
     * @param string|null $description
     * @return \App\Models\SiteSetting
     */
    public function setSetting($key, $value, $type = 'text', $group = null, $description = null)
    {
        return $this->siteSettingRepository->updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
    }

    /**
     * Get homepage settings
     *
     * @return array
     */
    public function getHomepageSettings()
    {
        $settings = $this->getByGroup('homepage');
        $data = [];
        foreach ($settings as $setting) {
            // Nếu là JSON, decode ra array
            if ($setting->type == 'json' && is_string($setting->value)) {
                $data[$setting->key] = json_decode($setting->value, true);
            } else {
                $data[$setting->key] = $setting->value;
            }
        }
        return $data;
    }
}