<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'tp_contacts';
    protected $fillable = [
        'uuid',
        'fullname',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];
}
