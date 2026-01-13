<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_contacts';

    protected $primaryKey = 'id_contact';

    protected $fillable = [
        'uuid',
        'fullname',
        'email',
        'phone',
        'subject',
        'message',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
