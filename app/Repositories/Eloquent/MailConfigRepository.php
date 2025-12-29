<?php

namespace App\Repositories\Eloquent;

use App\Models\MailConfig;
use App\Repositories\Interfaces\MailConfigRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class MailConfigRepository extends BaseRepository implements MailConfigRepositoryInterface
{
    public function __construct(MailConfig $model)
    {
        parent::__construct($model);
    }

    /**
     * Get active mail configuration
     *
     * @return \App\Models\MailConfig|null
     */
    public function getActiveConfig()
    {
        return $this->model->where('is_active', true)->first();
    }

    /**
     * Set active mail configuration
     *
     * @param int $id
     * @return bool
     */
    public function setActiveConfig($id)
    {
        // Deactivate all configs first
        $this->model->update(['is_active' => false]);

        // Activate the selected config
        return $this->model->where('id', $id)->update(['is_active' => true]);
    }

    /**
     * Get all mail configurations
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllConfigs()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create new mail configuration
     *
     * @param array $data
     * @return \App\Models\MailConfig
     */
    public function createConfig(array $data)
    {
        return $this->model->create($data);
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
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * Delete mail configuration
     *
     * @param int $id
     * @return bool
     */
    public function deleteConfig($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Test mail configuration
     *
     * @param array $config
     * @return array
     */
    public function testConfig(array $config)
    {
        try {
            // Set mail config temporarily
            config(['mail' => array_merge(config('mail'), $config)]);

            // Send test email
            Mail::raw('This is a test email from your Laravel application.', function ($message) {
                $message->to('test@example.com')
                        ->subject('Test Email Configuration');
            });

            return [
                'success' => true,
                'message' => 'Test email sent successfully!'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Find mail configuration by ID
     *
     * @param int $id
     * @param array $columns
     * @return \App\Models\MailConfig|null
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find($id, $columns);
    }
}