<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\About;
use Illuminate\Support\Str;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        About::create([
            'uuid' => Str::uuid()->toString(),
            'name_vn' => 'Giới thiệu về chúng tôi',
            'name_en' => 'About Us',
            'intro_vn' => 'Đội ngũ phục vụ chuyên nghiệp, tận tâm mang lại dịch vụ du lịch hoàn hảo nhất.',
            'intro_en' => 'Professional, dedicated team bringing the most perfect travel services.',
            'content_vn' => '<p>Chúng tôi là công ty lữ hành hàng đầu chuyên cung cấp các giải pháp tour du lịch cao cấp.</p>',
            'content_en' => '<p>We are a leading travel agency specializing in premium tour solutions.</p>',
            'image' => 'uploads/slider/slide_1.jpg', // Dùng tạm slide_1.jpg làm ảnh demo
            'link' => '/lien-he.html', // Link mặc định
            'status' => 1,
            'stt' => 1,
            'stats' => [
                ['icon' => 'uploads/icon/vietnam.png', 'value' => '10+', 'name_vn' => 'Năm kinh nghiệm', 'name_en' => 'Years of Experience'],
                ['icon' => 'uploads/icon/usa.png', 'value' => '900+', 'name_vn' => 'Khách hàng & Dự án', 'name_en' => 'Clients & Projects'],
                ['icon' => 'uploads/icon/ytb.png', 'value' => '20+', 'name_vn' => 'Giải pháp chuyên ngành', 'name_en' => 'Professional Solutions'],
                ['icon' => 'uploads/icon/zalo.png', 'value' => '50+', 'name_vn' => 'Đối tác uy tín chất lượng', 'name_en' => 'Prestigious Partners'],
            ]
        ]);
    }
}
