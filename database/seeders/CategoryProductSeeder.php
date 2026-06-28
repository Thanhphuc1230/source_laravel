<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cateRepo = app(\App\Repositories\Interfaces\CateProductRepositoryInterface::class);
        
        $category = $cateRepo->createWithAutoSlug([
            'name_vn' => 'Sản phẩm',
            'name_en' => 'Product',
            'keyword_vn' => 'sản phẩm, cửa hàng',
            'keyword_en' => 'product, store',
            'description_vn' => 'Danh mục sản phẩm',
            'description_en' => 'Product category',
            'image_vn' => null,
            'image_en' => null,
            'status' => true,
            'home' => false,
            'stt' => 0,
            'parent_id' => 0,
        ]);
    }
}
