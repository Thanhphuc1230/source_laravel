<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'tp_menus';

    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'uuid',
        'name_vn',
        'name_en',
        'link',
        'slug',
        'type',
        'parent_id',
        'object_id',
        'stt',
        'status',
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id_menu')
            ->where('status', 1)
            ->orderBy('stt', 'asc');
    }
}
