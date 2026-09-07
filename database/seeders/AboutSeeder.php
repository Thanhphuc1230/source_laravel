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
            'name_vn' => 'Về Thương Hiệu AURA LUXURY',
            'name_en' => 'About AURA LUXURY WATCHES',
            'intro_vn' => 'Nơi quy tụ những kiệt tác đồng hồ Thụy Sĩ đỉnh cao dành cho giới thượng lưu và nhà sưu tầm.',
            'intro_en' => 'Curating the world most exceptional Swiss timepieces for connoisseurs and collectors.',
            'content_vn' => '<p>AURA LUXURY WATCHES là điểm đến uy tín hàng đầu tại Việt Nam cho những người đam mê đồng hồ xa xỉ. Mỗi chiếc đồng hồ trao tay khách hàng đều là tác phẩm hoàn mỹ đi kèm chứng thư kiểm định độc lập và bảo hiểm quốc tế trọn gói.</p>',
            'content_en' => '<p>AURA LUXURY WATCHES is the premier destination in Vietnam for haute horlogerie enthusiasts. Every timepiece delivered is an authentic masterpiece accompanied by independent certification and global warranty.</p>',
            'image' => '',
            'link' => '/lien-he.html',
            'status' => 1,
            'stt' => 1,
            'stats' => [
                ['icon' => 'fa-solid fa-gem', 'value' => '100%', 'name_vn' => 'Chính Hãng 100%', 'name_en' => '100% Authentic'],
                ['icon' => 'fa-solid fa-crown', 'value' => '15+', 'name_vn' => 'Năm Uy Tín', 'name_en' => 'Years of Excellence'],
                ['icon' => 'fa-solid fa-award', 'value' => '5.000+', 'name_vn' => 'Khách Hàng Thượng Lưu', 'name_en' => 'VIP Clients'],
                ['icon' => 'fa-solid fa-shield-halved', 'value' => '5 Năm', 'name_vn' => 'Bảo Hành Quốc Tế', 'name_en' => 'Global Warranty'],
            ]
        ]);
    }
}
