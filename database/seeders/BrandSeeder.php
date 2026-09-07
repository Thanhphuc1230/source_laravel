<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name_vn' => 'Rolex',
                'name_en' => 'Rolex',
                'slug_vn' => 'rolex',
                'slug_en' => 'rolex',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 1,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Patek Philippe',
                'name_en' => 'Patek Philippe',
                'slug_vn' => 'patek-philippe',
                'slug_en' => 'patek-philippe',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 2,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Audemars Piguet',
                'name_en' => 'Audemars Piguet',
                'slug_vn' => 'audemars-piguet',
                'slug_en' => 'audemars-piguet',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 3,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Omega',
                'name_en' => 'Omega',
                'slug_vn' => 'omega',
                'slug_en' => 'omega',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 4,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Hublot',
                'name_en' => 'Hublot',
                'slug_vn' => 'hublot',
                'slug_en' => 'hublot',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 5,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Cartier',
                'name_en' => 'Cartier',
                'slug_vn' => 'cartier',
                'slug_en' => 'cartier',
                'image_vn' => '',
                'image_en' => '',
                'status' => 1,
                'stt' => 6,
                'uuid' => Str::uuid()->toString(),
            ],
        ];

        \Illuminate\Support\Facades\DB::table('tp_brands')->insert($brands);
    }
}
