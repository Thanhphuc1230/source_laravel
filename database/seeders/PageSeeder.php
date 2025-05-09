<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('tp_pages')->insert([
            'uuid' => Str::uuid(), // Generate a UUID
            'name_vn' => 'Giới thiệu', // {{ edit_1 }} Insert the value "sản phẩm"
            'name_en' => 'About us', // Optional English name
            'slug' => 'gioi-thieu', // You may want to create a slug
            'content_vn' => 'Giới thiệu', // Content in Vietnamese
            'content_en' => 'About us', // Optional content in English
            'status' => true,
            'stt' => 1,
            'keywords' => null,
            'description' => null,
            'parent_id' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
