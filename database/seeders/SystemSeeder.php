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
            'email' => 'info@basetravel.com',
            'address' => 'Lầu 5, Tòa nhà Travel, TP. Hồ Chí Minh',
            'phone' => '1800 6700',
            'footer_vn' => 'Chúng tôi tự hào mang đến những trải nghiệm hành trình độc đáo, dịch vụ du lịch nghỉ dưỡng cao cấp 5 sao phục vụ quý khách hàng.',
            'footer_en' => 'We are proud to deliver unique journeys and 5-star premium hospitality services.',
            'email_alert' => 'admin@basetravel.com',
            'facebook' => 'https://facebook.com/basetravel',
            'youtube' => 'https://youtube.com/basetravel',
            'twitter' => 'https://twitter.com/basetravel',
            'instagram' => 'https://instagram.com/basetravel',
            'zalo' => 'https://zalo.me/0909090909',

            'favicon' => 'images/logo/1719371505-logo.png',
            'logo' => 'images/logo/1719371505-logo.png',
            'name_vn' => 'BASE TRAVEL',
            'description' => 'BASE TRAVEL - Tour Du Lịch Uy Tín Hàng Đầu',
            'keyword' => 'base travel, du lich, tour du lich',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
