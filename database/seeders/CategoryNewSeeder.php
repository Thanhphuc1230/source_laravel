<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cateRepo = app(\App\Repositories\Interfaces\CateNewRepositoryInterface::class);
        
        $category = $cateRepo->createWithAutoSlug([
            'name_vn' => 'Tin tức',
            'name_en' => 'News',
            'keyword_vn' => 'tin tức, bài viết',
            'keyword_en' => 'news, articles',
            'description_vn' => 'Danh mục tin tức',
            'description_en' => 'News category',
            'image_vn' => null,
            'image_en' => null,
            'status' => true,
            'stt' => 0,
            'parent_id' => 0,
        ]);
    }
}
