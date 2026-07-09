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
                'intro_vn' => 'Giới thiệu sản phẩm '.$i,
                'intro_en' => 'Product intro '.$i,
                'price' => rand(100000, 500000),
                'price_old' => rand(100000, 500000),
                'content_vn' => 'Nội dung sản phẩm '.$i,
                'content_en' => 'Product content '.$i,
                'image_vn' => 'images/product/product-'.$i.'.webp',
                'image_en' => 'images/product/product-'.$i.'.webp',
                'image_detail' => null,
                'status' => true,
                'hot' => false,
                'stt' => $i,
                'keyword_vn' => 'keyword '.$i,
                'keyword_en' => 'keyword en '.$i,
                'description_vn' => 'Mô tả sản phẩm '.$i,
                'description_en' => 'Product description '.$i,
                'category_id' => 1,
            ]);
        }
    }
}
