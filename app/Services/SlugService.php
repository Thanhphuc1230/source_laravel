<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SlugService
{
    /**
     * Tạo slug duy nhất (không gắn ID)
     *
     * @param string $name Tên gốc để tạo slug
     * @param string $table Tên bảng để kiểm tra unique
     * @param string $column Tên cột slug (default: 'slug_vn')
     * @param int|null $id ID hiện tại để loại trừ khi update
     * @return string
     */
    public function generateUniqueSlug(string $name, string $table, string $column = 'slug_vn', ?int $id = null): string
    {
        return $this->generateUniqueSlugGlobal($name, $table, $column, $id);
    }

    /**
     * Tương thích ngược với hàm cũ nhưng không ghép ID vào slug
     */
    public function generateUniqueSlugWithId(string $name, int $id, string $table, string $column = 'slug_vn'): string
    {
        return $this->generateUniqueSlugGlobal($name, $table, $column, $id > 0 ? $id : null);
    }

    protected function getIdColumnName(string $table): string
    {
        $mapping = [
            'tp_products' => 'id_product',
            'tp_news' => 'id_new',
            'tp_cate_products' => 'id_cate_product',
            'tp_cate_news' => 'id_cate_new',
            'tp_pages' => 'id_page',
        ];

        return $mapping[$table] ?? 'id';
    }

    /**
     * Kiểm tra xem slug có bị trùng trong các bảng khác không
     */
    public function isSlugUniqueAcrossTables(string $slug, string $excludeTable = null, int $excludeId = null): bool
    {
        $tables = [
            'tp_pages' => 'id_page',
            'tp_products' => 'id_product',
            'tp_news' => 'id_new',
            'tp_cate_products' => 'id_cate_product',
            'tp_cate_news' => 'id_cate_new'
        ];

        foreach ($tables as $table => $idColumn) {
            $query = DB::table($table)->where(function($q) use ($slug) {
                $q->where('slug_vn', $slug)
                  ->orWhere('slug_en', $slug);
            });

            if ($excludeTable === $table && $excludeId) {
                $query->where($idColumn, '!=', $excludeId);
            }

            if ($query->exists()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Tạo slug duy nhất toàn hệ thống (không ghép ID)
     */
    public function generateUniqueSlugGlobal(string $name, string $table, string $column = 'slug_vn', ?int $id = null): string
    {
        $baseSlug = Str::slug($name);
        if (empty($baseSlug)) {
            $baseSlug = 'n-a';
        }
        $slug = $baseSlug;
        $counter = 1;

        while (!$this->isSlugUniqueAcrossTables($slug, $table, $id)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Tương thích ngược với hàm cũ
     */
    public function generateUniqueSlugWithIdGlobal(string $name, int $id, string $table, string $column = 'slug_vn'): string
    {
        return $this->generateUniqueSlugGlobal($name, $table, $column, $id > 0 ? $id : null);
    }
}