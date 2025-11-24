<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailConfig extends Model
{
    use HasFactory;

    protected $table = 'tp_mail_configs';

    protected $fillable = [
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_active',
        'additional_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'additional_config' => 'array',
        'port' => 'integer',
    ];

    /**
     * Get the active mail configuration
     */
    public static function getActiveConfig()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get mail configuration array for Laravel config
     */
    public function toConfigArray()
    {
        return [
            'driver' => $this->mailer,
            'host' => $this->host,
            'port' => $this->port,
            'username' => $this->username,
            'password' => $this->password,
            'encryption' => $this->encryption,
            'from' => [
                'address' => $this->from_address,
                'name' => $this->from_name,
            ],
        ];
    }
}
