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
                'link' => '#',
                'status' => 1,
                'image' => 'slide_1.jpg',
                'stt' => 1,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Hành Trình Du Lịch Châu Âu Cổ Kính',
                'link' => '#',
                'status' => 1,
                'image' => 'slide_2.jpg',
                'stt' => 2,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Nghỉ Dưỡng Thiên Đường Maldives',
                'link' => '#',
                'status' => 1,
                'image' => 'slide_3.jpg',
                'stt' => 3,
                'uuid' => Str::uuid()->toString(),
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
