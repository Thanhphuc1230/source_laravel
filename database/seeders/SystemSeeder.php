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
            'active_languages' => '["vi","en"]',
            'address_vn' => 'Lầu 5, Tòa nhà Travel, TP. Hồ Chí Minh',
            'address_en' => '5th Floor, Travel Building, Ho Chi Minh City',
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
            'name_en' => 'BASE TRAVEL',
            'description_vn' => 'BASE TRAVEL - Tour Du Lịch Uy Tín Hàng Đầu',
            'description_en' => 'BASE TRAVEL - Leading Prestigious Travel Tours',
            'keyword_vn' => 'base travel, du lich, tour du lich',
            'keyword_en' => 'base travel, travel, travel tours',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
