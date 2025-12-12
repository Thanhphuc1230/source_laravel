<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productRepo = app(\App\Repositories\Interfaces\ProductRepositoryInterface::class);
        
        for ($i = 1; $i <= 10; $i++) {
            $productRepo->createWithAutoSlug([
                'name_vn' => 'Sản phẩm '.$i,
                'name_en' => 'Product '.$i,
                'slug' => '',
                'intro_vn' => 'Giới thiệu sản phẩm '.$i,
                'intro_en' => 'Product intro '.$i,
                'price' => rand(100000, 500000),
                'price_old' => rand(100000, 500000),
                'content_vn' => 'Nội dung sản phẩm '.$i,
                'content_en' => 'Product content '.$i,
                'image' => 'product-'.$i.'.webp',
                'image_detail' => null,
                'status' => true,
                'hot' => false,
                'stt' => $i,
                'keywords' => 'keyword '.$i,
                'description' => 'Mô tả sản phẩm '.$i,
                'category_id' => 1,
            ]);
        }
    }
}
