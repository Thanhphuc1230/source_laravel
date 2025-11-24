<?php

namespace App\Repositories\Interfaces;

interface MailConfigRepositoryInterface
{
    /**
     * Get active mail configuration
     *
     * @return \App\Models\MailConfig|null
     */
    public function getActiveConfig();

    /**
     * Set active mail configuration
     *
     * @param int $id
     * @return bool
     */
    public function setActiveConfig($id);

    /**
     * Get all mail configurations
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllConfigs();

    /**
     * Create new mail configuration
     *
     * @param array $data
     * @return \App\Models\MailConfig
     */
    public function createConfig(array $data);

    /**
     * Update mail configuration
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateConfig(array $data, $id);

    /**
     * Delete mail configuration
     *
     * @param int $id
     * @return bool
     */
    public function deleteConfig($id);

    /**
     * Test mail configuration
     *
     * @param array $config
     * @return array
     */
    public function testConfig(array $config);
}