<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Trang Chủ',
                'name_en' => 'Home',
                'link' => '',
                'slug' => 'trang-chu',
                'type' => 'link',
                'parent_id' => 0,
                'object_id' => 0,
                'stt' => 0,
            ],
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Giới thiệu',
                'name_en' => 'About us',
                'link' => '',
                'slug' => 'gioi-thieu',
                'type' => 'page',
                'parent_id' => 0,
                'object_id' => 1,
                'stt' => 0,
            ],
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Sản Phẩm',
                'name_en' => 'Product',
                'link' => '',
                'slug' => 'san-pham',
                'type' => 'cate_product',
                'parent_id' => 0,
                'object_id' => 1,
                'stt' => 0,
            ],
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Tin Tức',
                'name_en' => 'News',
                'link' => '',
                'slug' => 'tin-tuc',
                'type' => 'cate_new',
                'parent_id' => 0,
                'object_id' => 1,
                'stt' => 0,
            ],
            [
                'uuid' => Str::uuid(),
                'name_vn' => 'Liên Hệ',
                'name_en' => 'Contact',
                'link' => 'lien-he',
                'slug' => 'lien-he',
                'type' => 'link',
                'parent_id' => 0,
                'object_id' => 0,
                'stt' => 2,
            ],
        ];

        DB::table('tp_menus')->insert($menus);
    }
}
