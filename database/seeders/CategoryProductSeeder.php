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
        DB::table('tp_cate_products')->insert([
            'uuid' => Str::uuid(),
            'name_vn' => 'Sản phẩm',
            'name_en' => 'Product',
            'slug' => 'san-pham',
            'keywords' => null,
            'description' => null,
            'status' => true,
            'home' => false,
            'stt' => 0,
            'parent_id' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
