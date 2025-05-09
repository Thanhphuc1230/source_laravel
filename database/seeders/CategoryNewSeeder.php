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
        DB::table('tp_cate_news')->insert([
            'uuid' => Str::uuid(),
            'name_vn' => 'Tin tức', 
            'name_en' => 'News', 
            'slug' => 'tin-tuc', 
            'keywords' => null,
            'description' => null,
            'status' => true,
            'stt' => 0,
            'parent_id' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
