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
        $downloader = app(\App\Services\DemoImageDownloaderService::class);

        $slide1 = $downloader->fetchAndSaveImage('luxury watch rolex dark banner', 1920, 750) ?? 'uploads/demo/banner_1.webp';
        $slide2 = $downloader->fetchAndSaveImage('luxury swiss watch patek banner', 1920, 750) ?? 'uploads/demo/banner_2.webp';
        $slide3 = $downloader->fetchAndSaveImage('luxury gold watch time banner', 1920, 750) ?? 'uploads/demo/banner_3.webp';

        $sliders = [
            [
                'name_vn' => 'Bộ Sưu Tập Tuyệt Tác Thời Gian 2026',
                'name_en' => 'Haute Horlogerie Timepiece Collection 2026',
                'link' => '#',
                'status' => 1,
                'image_desktop_vn' => $slide1,
                'image_desktop_en' => $slide1,
                'image_mobile_vn' => $slide1,
                'image_mobile_en' => $slide1,
                'stt' => 1,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Đỉnh Cao Chế Tác Đồng Hồ Thụy Sĩ',
                'name_en' => 'The Pinnacle of Swiss Watchmaking',
                'link' => '#',
                'status' => 1,
                'image_desktop_vn' => $slide2,
                'image_desktop_en' => $slide2,
                'image_mobile_vn' => $slide2,
                'image_mobile_en' => $slide2,
                'stt' => 2,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Đặc Quyền Sở Hữu Phiên Bản Giới Hạn',
                'name_en' => 'Exclusive Privilege for Limited Editions',
                'link' => '#',
                'status' => 1,
                'image_desktop_vn' => $slide3,
                'image_desktop_en' => $slide3,
                'image_mobile_vn' => $slide3,
                'image_mobile_en' => $slide3,
                'stt' => 3,
                'uuid' => Str::uuid()->toString(),
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
