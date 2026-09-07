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
                'title_vn' => '100% Chính Hãng',
                'title_en' => '100% Certified Authentic',
                'content_vn' => 'Cam kết đền tiền gấp 10 lần nếu phát hiện hàng không chính hãng.',
                'content_en' => 'Full certificate of authenticity and 10x money-back guarantee.',
                'image' => 'fa-solid fa-award',
                'status' => 1,
                'stt' => 1,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'title_vn' => 'Bảo Hành Quốc Tế 5 Năm',
                'title_en' => '5-Year International Warranty',
                'content_vn' => 'Dịch vụ bảo dưỡng và lau dầu định kỳ bởi chuyên gia Thụy Sĩ.',
                'content_en' => 'Complimentary maintenance and servicing by Swiss master horologists.',
                'image' => 'fa-solid fa-shield-halved',
                'status' => 1,
                'stt' => 2,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'title_vn' => 'Thẩm Định Độc Lập',
                'title_en' => 'Expert Horological Appraisal',
                'content_vn' => 'Quy trình kiểm tra 32 bước nghiêm ngặt về độ chính xác và xuất xứ.',
                'content_en' => '32-step inspection on mechanical precision and provenance.',
                'image' => 'fa-solid fa-gem',
                'status' => 1,
                'stt' => 3,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'title_vn' => 'Giao Hàng Bảo Mật VIP',
                'title_en' => 'VIP Insured Express Delivery',
                'content_vn' => 'Vận chuyển hỏa tốc bọc thép và bảo hiểm 100% giá trị sản phẩm.',
                'content_en' => 'Armored secure transport with 100% full-value transit insurance.',
                'image' => 'fa-solid fa-truck-fast',
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
