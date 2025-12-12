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
            'slug' => '',
            'keywords' => null,
            'description' => null,
            'status' => true,
            'stt' => 0,
            'parent_id' => 0,
        ]);
    }
}
