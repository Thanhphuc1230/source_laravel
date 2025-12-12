<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SlugService
{
    /**
     * Tạo slug duy nhất với format: name-slug-id
     * Ví dụ: san-pham-1, tin-tuc-5
     *
     * @param string $name Tên gốc để tạo slug
     * @param int $id ID của entity
     * @param string $table Tên bảng để kiểm tra unique
     * @param string $column Tên cột slug (default: 'slug')
     * @return string
     */
    public function generateUniqueSlugWithId(string $name, int $id, string $table, string $column = 'slug'): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug . '-' . $id;

        $idColumn = $this->getIdColumnName($table);
        $counter = 1;
        $originalSlug = $slug;

        while (DB::table($table)->where($column, $slug)->where($idColumn, '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
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
     * (Để đảm bảo không trùng với các entity khác trong hệ thống)
     *
     * @param string $slug
     * @param string $excludeTable Bảng loại trừ (để khi update không check chính nó)
     * @param int|null $excludeId ID loại trừ
     * @return bool
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
            $query = DB::table($table)->where('slug', $slug)->where('status', 1);

            // Loại trừ bảng và ID hiện tại (để khi update)
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
     * Tạo slug duy nhất với ID và đảm bảo không trùng trong toàn bộ hệ thống
     *
     * @param string $name
     * @param int $id
     * @param string $table
     * @param string $column
     * @return string
     */
    public function generateUniqueSlugWithIdGlobal(string $name, int $id, string $table, string $column = 'slug'): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug . '-' . $id;

        // Nếu slug đã tồn tại trong hệ thống (không phải của entity hiện tại), thêm hậu tố
        $counter = 1;
        $originalSlug = $slug;

        while (!$this->isSlugUniqueAcrossTables($slug, $table, $id)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}