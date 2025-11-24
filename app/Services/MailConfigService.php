<?php

namespace App\Services;

use App\Repositories\Interfaces\MailConfigRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class MailConfigService
{
    protected $mailConfigRepository;

    public function __construct(MailConfigRepositoryInterface $mailConfigRepository)
    {
        $this->mailConfigRepository = $mailConfigRepository;
    }

    /**
     * Get active mail configuration or fallback to .env
     *
     * @return array
     */
    public function getActiveMailConfig()
    {
        return CacheService::remember(
            CacheService::TAGS['system'] ?? 'system',
            'active_mail_config',
            CacheService::getTtl('long'),
            function () {
                $activeConfig = $this->mailConfigRepository->getActiveConfig();

                if ($activeConfig) {
                    return $activeConfig->toConfigArray();
                }

                // Fallback to .env config
                return [
                    'driver' => config('mail.driver'),
                    'host' => config('mail.host'),
                    'port' => config('mail.port'),
                    'username' => config('mail.username'),
                    'password' => config('mail.password'),
                    'encryption' => config('mail.encryption'),
                    'from' => [
                        'address' => config('mail.from.address'),
                        'name' => config('mail.from.name'),
                    ],
                ];
            }
        );
    }

    /**
     * Get all mail configurations
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllConfigs()
    {
        return CacheService::remember(
            CacheService::TAGS['system'] ?? 'system',
            'all_mail_configs',
            CacheService::getTtl('medium'),
            fn () => $this->mailConfigRepository->getAllConfigs()
        );
    }

    /**
     * Create new mail configuration
     *
     * @param array $data
     * @return \App\Models\MailConfig
     */
    public function createConfig(array $data)
    {
        // Clear cache
        CacheService::forgetTag(CacheService::TAGS['system'] ?? 'system');

        return $this->mailConfigRepository->createConfig($data);
    }

    /**
     * Update mail configuration
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateConfig(array $data, $id)
    {
        // Clear cache
        CacheService::forgetTag(CacheService::TAGS['system'] ?? 'system');

        return $this->mailConfigRepository->updateConfig($data, $id);
    }

    /**
     * Set active mail configuration
     *
     * @param int $id
     * @return bool
     */
    public function setActiveConfig($id)
    {
        // Clear cache
        CacheService::forgetTag(CacheService::TAGS['system'] ?? 'system');

        return $this->mailConfigRepository->setActiveConfig($id);
    }

    /**
     * Delete mail configuration
     *
     * @param int $id
     * @return bool
     */
    public function deleteConfig($id)
    {
        // Clear cache
        CacheService::forgetTag(CacheService::TAGS['system'] ?? 'system');

        return $this->mailConfigRepository->deleteConfig($id);
    }

    /**
     * Test mail configuration
     *
     * @param array $config
     * @return array
     */
    public function testConfig(array $config)
    {
        return $this->mailConfigRepository->testConfig($config);
    }

    /**
     * Get mail configuration by ID
     *
     * @param int $id
     * @return \App\Models\MailConfig|null
     */
    public function getConfigById($id)
    {
        return $this->mailConfigRepository->find($id);
    }
}