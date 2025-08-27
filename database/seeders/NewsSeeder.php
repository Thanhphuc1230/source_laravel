<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('tp_news')->insert([
                'uuid' => Str::uuid(),
                'name_vn' => 'Tin tức '.$i,
                'name_en' => 'News '.$i,
                'slug' => Str::slug('tin-tuc-'.$i),
                'image' => 'product-'.$i.'.webp',
                'intro_vn' => 'Giới thiệu tin tức '.$i,
                'intro_en' => 'News intro '.$i,
                'content_vn' => 'Nội dung tin tức '.$i,
                'content_en' => 'News content '.$i,
                'keywords' => 'keyword '.$i,
                'description' => 'Mô tả tin tức '.$i,
                'category_id' => 1,
                'status' => true,
                'home' => false,
                'stt' => $i,
                'views' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
