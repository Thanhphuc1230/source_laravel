<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Hero Section
        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'hero_title'],
            [
                'type' => 'text',
                'value' => 'Premium Research Peptides',
                'group' => 'homepage',
                'description' => 'Tiêu đề chính hero section'
            ]
        );

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'hero_subtitle'],
            [
                'type' => 'text',
                'value' => 'Tested. Verified. Trusted.',
                'group' => 'homepage',
                'description' => 'Phụ đề hero section'
            ]
        );

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'hero_description'],
            [
                'type' => 'editor',
                'value' => 'Nupex peptides are formulated for research, produced and scientifically verified, and backed by third-party lab data to ensure that you can trust the accuracy, consistency, and integrity of every compound.',
                'group' => 'homepage',
                'description' => 'Mô tả hero section'
            ]
        );

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'hero_button_text'],
            [
                'type' => 'text',
                'value' => 'Shop Now',
                'group' => 'homepage',
                'description' => 'Text button hero section'
            ]
        );

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'hero_button_link'],
            [
                'type' => 'text',
                'value' => '/shop',
                'group' => 'homepage',
                'description' => 'Link button hero section'
            ]
        );

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'hero_image'],
            [
                'type' => 'image',
                'value' => '/uploads/hero-bg.jpg',
                'group' => 'homepage',
                'description' => 'Hình ảnh hero section'
            ]
        );

        // Features Section
        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'features'],
            [
                'type' => 'json',
                'value' => json_encode([
                    [
                        'icon' => 'ri-verified-badge-line',
                        'title' => 'HPLC-Tested for Verified Purity',
                    ],
                    [
                        'icon' => 'ri-flask-line',
                        'title' => 'Scientifically verified',
                    ],
                    [
                        'icon' => 'ri-truck-line',
                        'title' => 'Next Day Delivery (UK)',
                        'subtitle' => '& International Shipping'
                    ],
                    [
                        'icon' => 'ri-customer-service-line',
                        'title' => 'Expert Support Available',
                    ],
                ]),
                'group' => 'homepage',
                'description' => 'Danh sách tính năng nổi bật'
            ]
        );
    }
}
