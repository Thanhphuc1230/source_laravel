<?php

namespace Database\Seeders;

use App\Models\NewsSetting;
use Illuminate\Database\Seeder;

class NewsSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'news_pagination',
                'value' => '8',
                'type' => 'number',
                'group' => 'general',
                'description' => 'Số lượng tin tức hiển thị trên một trang',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'news_font_size',
                'value' => '16px',
                'type' => 'text',
                'group' => 'style',
                'description' => 'Kích thước font chữ tiêu đề tin tức',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'news_show_intro',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Hiển thị giới thiệu ngắn của tin tức',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'news_click_image_detail',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Cho phép click vào hình ảnh tin tức để xem chi tiết',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'news_title_color',
                'value' => '#064e3b',
                'type' => 'text',
                'group' => 'style',
                'description' => 'Màu sắc của tiêu đề tin tức',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'key' => 'news_category_color',
                'value' => '#b45309',
                'type' => 'text',
                'group' => 'style',
                'description' => 'Màu sắc của tên danh mục tin tức',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'key' => 'news_banner_category',
                'value' => 'uploads/news-setting/banner_category.jpg',
                'type' => 'text',
                'group' => 'banner',
                'description' => 'Banner danh mục tin tức',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'key' => 'news_banner_detail',
                'value' => 'uploads/news-setting/banner_detail.jpg',
                'type' => 'text',
                'group' => 'banner',
                'description' => 'Banner chi tiết tin tức',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        NewsSetting::truncate();

        foreach ($settings as $setting) {
            NewsSetting::create($setting);
        }
    }
}
