<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Slider;
use Illuminate\Support\Str;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'name_vn' => 'Khám Phá Vịnh Hạ Long Hùng Vĩ',
                'name_en' => 'Explore Majestic Halong Bay',
                'link' => '#',
                'status' => 1,
                'image_desktop_vn' => 'slide_1.jpg',
                'image_desktop_en' => 'slide_1.jpg',
                'image_mobile_vn' => 'slide_1_m.jpg',
                'image_mobile_en' => 'slide_1_m.jpg',
                'stt' => 1,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Hành Trình Du Lịch Châu Âu Cổ Kính',
                'name_en' => 'Journey through Historic Europe',
                'link' => '#',
                'status' => 1,
                'image_desktop_vn' => 'slide_2.jpg',
                'image_desktop_en' => 'slide_2.jpg',
                'image_mobile_vn' => 'slide_2_m.jpg',
                'image_mobile_en' => 'slide_2_m.jpg',
                'stt' => 2,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Nghỉ Dưỡng Thiên Đường Maldives',
                'name_en' => 'Relax in Paradise Maldives',
                'link' => '#',
                'status' => 1,
                'image_desktop_vn' => 'slide_3.jpg',
                'image_desktop_en' => 'slide_3.jpg',
                'image_mobile_vn' => null, // Demo Fallback về ảnh Desktop
                'image_mobile_en' => null,
                'stt' => 3,
                'uuid' => Str::uuid()->toString(),
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
