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
        $topic = config('demo.current_topic', 'watches');

        if ($topic === 'watches') {
            DB::table('tp_systems')->insert([
                'email' => 'contact@auraluxurywatches.com',
                'active_languages' => '["vi","en"]',
                'address_vn' => '188 Đồng Khởi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
                'address_en' => '188 Dong Khoi, Ben Nghe Ward, District 1, Ho Chi Minh City',
                'phone' => '0909 888 999',
                'footer_vn' => 'AURA LUXURY WATCHES - Tuyệt tác thời gian đỉnh cao. Nhà phân phối đồng hồ Thụy Sĩ và các thương hiệu xa xỉ hàng đầu thế giới với cam kết 100% chính hãng và bảo hành quốc tế.',
                'footer_en' => 'AURA LUXURY WATCHES - Pinnacle of Haute Horlogerie. Authorized distributor of Swiss and world-leading luxury watchmakers with 100% authenticity guarantee.',
                'email_alert' => 'admin@auraluxurywatches.com',
                'facebook' => 'https://facebook.com/auraluxury',
                'youtube' => 'https://youtube.com/auraluxury',
                'twitter' => 'https://twitter.com/auraluxury',
                'instagram' => 'https://instagram.com/auraluxury',
                'zalo' => 'https://zalo.me/0909888999',

                'favicon' => '',
                'logo' => '',
                'name_vn' => 'AURA LUXURY WATCHES',
                'name_en' => 'AURA LUXURY WATCHES',
                'description_vn' => 'AURA LUXURY WATCHES - Đỉnh Cao Đồng Hồ Thụy Sĩ & Xa Xỉ Chính Hãng',
                'description_en' => 'AURA LUXURY WATCHES - Authentic Swiss & Haute Horlogerie Timepieces',
                'keyword_vn' => 'dong ho cao cap, dong ho thuy si, rolex, patek philippe, hublot, omega',
                'keyword_en' => 'luxury watches, swiss watches, rolex, patek philippe, hublot, omega',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
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

                'favicon' => '',
                'logo' => '',
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
}
