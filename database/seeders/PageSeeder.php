<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $pageRepo = app(\App\Repositories\Interfaces\PageRepositoryInterface::class);
        
        $page = $pageRepo->createWithAutoSlug([
            'name_vn' => 'Giới thiệu',
            'name_en' => 'About us',
            'content_vn' => 'Giới thiệu',
            'content_en' => 'About us',
            'status' => true,
            'stt' => 1,
            'keyword_vn' => 'giới thiệu, công ty',
            'keyword_en' => 'about us, company',
            'description_vn' => 'Trang giới thiệu công ty',
            'description_en' => 'About us page',
            'image_vn' => null,
            'image_en' => null,
            'parent_id' => 0,
        ]);
    }
}
