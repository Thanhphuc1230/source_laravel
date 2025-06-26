<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('tp_products')->insert([
                'uuid' => Str::uuid(),
                'name_vn' => 'Sản phẩm ' . $i,
                'name_en' => 'Product ' . $i,
                'slug' => Str::slug('san-pham-' . $i),
                'intro_vn' => 'Giới thiệu sản phẩm ' . $i,
                'intro_en' => 'Product intro ' . $i,
                'price' => rand(100000, 500000),
                'price_old' => rand(100000, 500000),
                'content_vn' => 'Nội dung sản phẩm ' . $i,
                'content_en' => 'Product content ' . $i,
                'image' => 'product-' . $i . '.jpg',
                'image_detail' => null,
                'status' => true,
                'hot' => false,
                'stt' => $i,
                'keywords' => 'keyword ' . $i,
                'description' => 'Mô tả sản phẩm ' . $i,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 