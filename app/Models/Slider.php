<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory;

    protected $table = 'tp_sliders';

    protected $primaryKey = 'id_slider';

    protected $fillable = [
        'name_vn',
        'name_en',
        'link',
        'image_desktop_vn',
        'image_desktop_en',
        'image_mobile_vn',
        'image_mobile_en',
        'stt',
        'uuid',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getImageDesktopVnAttribute($value)
    {
        return $this->resolveImageUrl($value);
    }

    public function getImageDesktopEnAttribute($value)
    {
        return $this->resolveImageUrl($value);
    }

    public function getImageMobileVnAttribute($value)
    {
        $image = $value ?: ($this->attributes['image_desktop_vn'] ?? null);
        return $this->resolveImageUrl($image);
    }

    public function getImageMobileEnAttribute($value)
    {
        $image = $value ?: ($this->attributes['image_desktop_en'] ?? null);
        return $this->resolveImageUrl($image);
    }

    public function getImageDesktopAttribute($value = null)
    {
        if ($value !== null) {
            return $this->resolveImageUrl($value);
        }

        $locale = app()->getLocale();
        return $this->{'image_desktop_' . $locale} ?: ($this->image_desktop_vn ?: $this->image_desktop_en);
    }

    public function getImageMobileAttribute($value = null)
    {
        if ($value !== null) {
            return $this->resolveImageUrl($value);
        }

        $locale = app()->getLocale();
        return $this->{'image_mobile_' . $locale} ?: ($this->image_mobile_vn ?: ($this->image_mobile_en ?: $this->image_desktop));
    }

    public function getImageVnAttribute($value = null)
    {
        return $this->image_desktop_vn;
    }

    public function getImageEnAttribute($value = null)
    {
        return $this->image_desktop_en;
    }

    public function getImageAttribute($value = null)
    {
        return $this->image_desktop;
    }
}
