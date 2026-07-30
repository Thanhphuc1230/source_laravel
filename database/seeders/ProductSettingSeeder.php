<?php

namespace Database\Seeders;

use App\Models\ProductSetting;
use Illuminate\Database\Seeder;

class ProductSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'products_pagination',
                'value' => '8',
                'type' => 'number',
                'group' => 'general',
                'description' => 'Số lượng sản phẩm hiển thị trên một trang',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'products_font_size',
                'value' => '16px',
                'type' => 'text',
                'group' => 'style',
                'description' => 'Kích thước font chữ tiêu đề sản phẩm',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'products_show_intro',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Hiển thị giới thiệu ngắn của sản phẩm',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'products_click_image_detail',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Cho phép click vào hình ảnh sản phẩm để xem chi tiết',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'products_title_color',
                'value' => '#064e3b',
                'type' => 'text',
                'group' => 'style',
                'description' => 'Màu sắc của tiêu đề sản phẩm',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'key' => 'products_category_color',
                'value' => '#b45309',
                'type' => 'text',
                'group' => 'style',
                'description' => 'Màu sắc của tên danh mục sản phẩm',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'key' => 'products_banner_category',
                'value' => '',
                'type' => 'text',
                'group' => 'banner',
                'description' => 'Banner danh mục sản phẩm',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'key' => 'products_banner_detail',
                'value' => '',
                'type' => 'text',
                'group' => 'banner',
                'description' => 'Banner chi tiết sản phẩm',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        ProductSetting::truncate();

        foreach ($settings as $setting) {
            ProductSetting::create($setting);
        }
    }
}
