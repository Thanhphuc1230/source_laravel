<?php

namespace App\Traits;

trait AutoImagePathsTrait
{
    public function getImageVnAttribute($value)
    {
        return $this->resolveImageUrl($value);
    }

    public function getImageEnAttribute($value)
    {
        return $this->resolveImageUrl($value);
    }

    public function getImageAttribute($value = null)
    {
        if ($value !== null) {
            return $this->resolveImageUrl($value);
        }

        $locale = app()->getLocale();
        return $this->{'image_' . $locale} ?: ($this->image_vn ?: $this->image_en);
    }

    public function getAvatarAttribute($value)
    {
        return $this->resolveImageUrl($value) ?: asset('images/users/default.jpg');
    }

    public function getLogoAttribute($value)
    {
        return $this->resolveImageUrl($value);
    }

    public function getFaviconAttribute($value)
    {
        return $this->resolveImageUrl($value);
    }

    protected function resolveImageUrl($value)
    {
        if (empty($value)) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        if (str_starts_with($value, 'images/')) {
            return asset($value);
        }

        // Tương thích ngược: tự lấy folder tương ứng nếu chỉ lưu tên file
        $folder = $this->getFolderFromModel();
        return asset("images/{$folder}/{$value}");
    }

    protected function getFolderFromModel()
    {
        if (isset($this->imageFolder)) {
            return $this->imageFolder;
        }

        $className = class_basename($this);
        return match ($className) {
            'News' => 'news',
            'Product' => 'product',
            'CateNew' => 'cate_new',
            'CateProduct' => 'cate_product',
            'Page' => 'page',
            'Brand' => 'brand',
            'Feature' => 'feature',
            'FeedBack' => 'feedback',
            'Gallery' => 'gallery',
            'Slider' => 'slider',
            'System' => 'logo',
            'User' => 'users',
            default => strtolower($className),
        };
    }
}
