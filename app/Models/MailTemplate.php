<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    use HasFactory;

    protected $table = 'tp_mail_templates';

    protected $fillable = [
        'name',
        'subject',
        'content',
        'variables',
        'type',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];
}
