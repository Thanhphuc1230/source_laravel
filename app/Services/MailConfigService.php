<?php

namespace App\Services;

use App\Repositories\Interfaces\MailConfigRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

    /**
     * Get all mail configurations
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllConfigs()
    {
        return $this->mailConfigRepository->getAllConfigs();
    }

    /**
     * Create new mail configuration
     *
     * @param array $data
     * @return \App\Models\MailConfig
     */
    public function createConfig(array $data)
    {
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

    /**
     * Create a custom mailer instance with DB config
     *
     * @return \Illuminate\Mail\Mailer
     */
    public function createMailer()
    {
        $config = $this->getActiveMailConfig();

        // For port 587 (STARTTLS), use false to not wrap connection in SSL initially
        // Server will upgrade connection to TLS via STARTTLS command
        $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
            $config['host'],
            $config['port'],
            false
        );
        
        $transport->setUsername($config['username']);
        $transport->setPassword($config['password']);

        // Set stream options for SSL/TLS connections
        $stream = $transport->getStream();
        if ($stream instanceof \Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream) {
            $stream->setStreamOptions([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);
        }

        $mailer = new \Illuminate\Mail\Mailer(
            'custom',
            app('view'),
            $transport,
            app('events')
        );

        $mailer->alwaysFrom($config['from']['address'], $config['from']['name']);

        return $mailer;
    }
}