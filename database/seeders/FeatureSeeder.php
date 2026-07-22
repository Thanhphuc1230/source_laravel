<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;
use Illuminate\Support\Str;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'title_vn' => 'Giá Tốt Nhất',
                'title_en' => 'Best Price Guarantee',
                'content_vn' => 'Cam kết giá tour rẻ nhất và chất lượng tốt nhất.',
                'content_en' => 'We guarantee the best value and top-notch tour quality.',
                'image' => 'fa-solid fa-tags',
                'status' => 1,
                'stt' => 1,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'title_vn' => 'Hỗ Trợ 24/7',
                'title_en' => '24/7 Support Desk',
                'content_vn' => 'Tổng đài hỗ trợ tư vấn khách hàng mọi lúc mọi nơi.',
                'content_en' => 'Our support hotline is available anytime, anywhere.',
                'image' => 'fa-solid fa-headset',
                'status' => 1,
                'stt' => 2,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'title_vn' => 'Thanh Toán An Toàn',
                'title_en' => 'Secure Payments',
                'content_vn' => 'Phương thức thanh toán đa dạng và bảo mật tuyệt đối.',
                'content_en' => 'Flexible payment methods with absolute encryption.',
                'image' => 'fa-solid fa-shield-halved',
                'status' => 1,
                'stt' => 3,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'title_vn' => 'Dịch Vụ Đẳng Cấp',
                'title_en' => 'Premium Services',
                'content_vn' => 'Đảm bảo trải nghiệm du lịch nghỉ dưỡng 5 sao sang trọng.',
                'content_en' => 'Ensuring luxury 5-star resort and travel experiences.',
                'image' => 'fa-solid fa-star',
                'status' => 1,
                'stt' => 4,
                'uuid' => Str::uuid()->toString(),
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
