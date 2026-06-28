<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $newsRepo = app(\App\Repositories\Interfaces\NewsRepositoryInterface::class);
        
        for ($i = 1; $i <= 10; $i++) {
            $newsRepo->createWithAutoSlug([
                'name_vn' => 'Tin tức '.$i,
                'name_en' => 'News '.$i,
                'image_vn' => 'product-'.$i.'.webp',
                'image_en' => 'product-'.$i.'.webp',
                'intro_vn' => 'Giới thiệu tin tức '.$i,
                'intro_en' => 'News intro '.$i,
                'content_vn' => 'Nội dung tin tức '.$i,
                'content_en' => 'News content '.$i,
                'keyword_vn' => 'keyword '.$i,
                'keyword_en' => 'keyword en '.$i,
                'description_vn' => 'Mô tả tin tức '.$i,
                'description_en' => 'News description '.$i,
                'category_id' => 1,
                'status' => true,
                'home' => false,
                'stt' => $i,
                'views' => 0,
            ]);
        }
    }
}
