<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $newsRepo = app(\App\Repositories\Interfaces\NewsRepositoryInterface::class);
        $downloader = app(\App\Services\DemoImageDownloaderService::class);

        $articles = [
            [
                'name_vn' => 'Nghệ Thuật Chế Tác Đồng Hồ Cơ Khí Thụy Sĩ - Đỉnh Cao Của Sự Tinh Xảo',
                'name_en' => 'The Art of Swiss Mechanical Watchmaking - Pinnacle of Precision',
                'keyword' => 'swiss watchmaker movement',
                'intro_vn' => 'Hành trình hàng trăm năm lịch sử đằng sau những cỗ máy thời gian triệu đô được chế tác thủ công.',
                'intro_en' => 'Centuries of heritage and craftsmanship behind million-dollar handcrafted timepieces.',
                'content_vn' => '<p>Mỗi chiếc đồng hồ Thụy Sĩ là một tác phẩm nghệ thuật phức tạp bao gồm hàng trăm chi tiết siêu nhỏ được mài giũa, đánh bóng và lắp ráp hoàn toàn thủ công bởi các bậc thầy nghệ nhân hàng đầu vùng Thung lũng Joux.</p>',
                'content_en' => '<p>Every Swiss timepiece is an intricate work of art comprising hundreds of microscopic components, hand-finished and assembled by master horologists in the Vallée de Joux.</p>',
                'category_id' => 1,
            ],
            [
                'name_vn' => 'Top 5 Chiếc Đồng Hồ Xa Xỉ Được Săn Đón Nhất Mọi Thời Đại',
                'name_en' => 'Top 5 Most Coveted Luxury Watches of All Time',
                'keyword' => 'luxury watch collection rolex patek',
                'intro_vn' => 'Khám phá những cái tên huyền thoại giữ giá và tăng trưởng vượt bậc trên thị trường sưu tầm quốc tế.',
                'intro_en' => 'Discover legendary timepieces that retain and appreciate exponentially in value.',
                'content_vn' => '<p>Từ Patek Philippe Nautilus, Rolex Daytona đến Audemars Piguet Royal Oak - đây không chỉ là phụ kiện thời trang mà còn là tài sản tích sản đẳng cấp của giới siêu giàu toàn cầu.</p>',
                'content_en' => '<p>From Patek Philippe Nautilus to Rolex Daytona and Audemars Piguet Royal Oak - these are not merely accessories, but prestigious alternative investment assets.</p>',
                'category_id' => 1,
            ],
            [
                'name_vn' => 'Hướng Dẫn Sử Dụng & Bảo Quản Đồng Hồ Cơ Automatic Đúng Cách',
                'name_en' => 'Ultimate Guide to Caring & Maintaining Automatic Watches',
                'keyword' => 'automatic watch winder leather',
                'intro_vn' => 'Những lưu ý quan trọng về cách lên cót, tránh nhiễm từ và bảo dưỡng định kỳ giúp đồng hồ luôn bền đẹp.',
                'intro_en' => 'Essential tips on winding, anti-magnetism and regular servicing to keep your timepiece pristine.',
                'content_vn' => '<p>Đồng hồ cơ cần được đeo hoặc đặt trong hộp xoay tự động (watch winder) để giữ cho dầu bôi trơn luôn hoạt động trơn tru. Tránh đặt đồng hồ gần loa thùng, tủ lạnh hoặc máy tính để chống nhiễm từ trường.</p>',
                'content_en' => '<p>Mechanical watches need regular winding or storage in a watch winder. Keep away from high magnetic sources such as speakers and electronic devices.</p>',
                'category_id' => 1,
            ],
        ];

        foreach ($articles as $idx => $art) {
            $imagePath = $downloader->fetchAndSaveImage($art['keyword'], 800, 500) ?? ('uploads/demo/news_' . ($idx + 1) . '.webp');

            $newsRepo->createWithAutoSlug([
                'name_vn' => $art['name_vn'],
                'name_en' => $art['name_en'],
                'image_vn' => $imagePath,
                'image_en' => $imagePath,
                'intro_vn' => $art['intro_vn'],
                'intro_en' => $art['intro_en'],
                'content_vn' => $art['content_vn'],
                'content_en' => $art['content_en'],
                'category_id' => $art['category_id'],
                'status' => true,
                'stt' => $idx + 1,
                'keyword_vn' => 'tin tuc, dong ho, ' . $art['name_vn'],
                'keyword_en' => 'news, watch, ' . $art['name_en'],
                'description_vn' => $art['intro_vn'],
                'description_en' => $art['intro_en'],
                'uuid' => Str::uuid()->toString(),
            ]);
        }
    }
}
