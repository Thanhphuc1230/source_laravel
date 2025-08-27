<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tp_systems')->insert([
            'email' => 'info@gmail.com',
            'address' => 'Hà Nội, Việt Nam',
            'phone' => '0909090909',
            'footer_vn' => 'Sample Footer',
            'footer_en' => 'Sample Footer',
            'email_alert' => 'thanhphuc15052001@gmail.com',
            'facebook' => 'https://facebook.com/sample',
            'youtube' => 'https://youtube.com/sample',
            'twitter' => 'https://twitter.com/sample',
            'instagram' => 'https://instagram.com/sample',
            'zalo' => 'https://zalo.com/sample',

            'favicon' => '1719371505-logo.png',
            'logo' => '1719371505-logo.png',
            'name_vn' => 'Công ty TNHH Thương mại và Dịch vụ ',
            'description' => 'Công ty TNHH Thương mại và Dịch vụ ',
            'keyword' => 'Công ty TNHH Thương mại và Dịch vụ ',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
