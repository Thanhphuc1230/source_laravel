<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cateRepo = app(\App\Repositories\Interfaces\CateProductRepositoryInterface::class);
        $downloader = app(\App\Services\DemoImageDownloaderService::class);

        $img1 = $downloader->fetchAndSaveImage('luxury watch men', 600, 600) ?? 'uploads/demo/watch_men.webp';
        $img2 = $downloader->fetchAndSaveImage('luxury watch women', 600, 600) ?? 'uploads/demo/watch_women.webp';
        $img3 = $downloader->fetchAndSaveImage('luxury watch automatic', 600, 600) ?? 'uploads/demo/watch_auto.webp';
        $img4 = $downloader->fetchAndSaveImage('luxury watch limited', 600, 600) ?? 'uploads/demo/watch_limited.webp';

        // Category 1: Đồng Hồ Nam
        $cateRepo->createWithAutoSlug([
            'name_vn' => 'Đồng Hồ Nam',
            'name_en' => "Men's Luxury Watches",
            'keyword_vn' => 'dong ho nam, dong ho nam cao cap',
            'keyword_en' => 'mens watches, luxury watches for men',
            'description_vn' => 'Tuyệt tác đồng hồ nam khẳng định bản lĩnh, vị thế và đẳng cấp phái mạnh.',
            'description_en' => "Masterpieces for gentlemen expressing distinction, status and prestige.",
            'image_vn' => $img1,
            'image_en' => $img1,
            'status' => true,
            'home' => true,
            'stt' => 1,
            'parent_id' => 0,
        ]);

        // Category 2: Đồng Hồ Nữ
        $cateRepo->createWithAutoSlug([
            'name_vn' => 'Đồng Hồ Nữ',
            'name_en' => "Women's Luxury Watches",
            'keyword_vn' => 'dong ho nu, dong ho nu cao cap',
            'keyword_en' => 'womens watches, luxury watches for women',
            'description_vn' => 'Biểu tượng của vẻ đẹp kiêu sa, quyến rũ và thanh lịch thời thượng.',
            'description_en' => 'Embodiment of feminine elegance, glamour and contemporary luxury.',
            'image_vn' => $img2,
            'image_en' => $img2,
            'status' => true,
            'home' => true,
            'stt' => 2,
            'parent_id' => 0,
        ]);

        // Category 3: Đồng Hồ Cơ Automatic
        $cateRepo->createWithAutoSlug([
            'name_vn' => 'Đồng Hồ Cơ Automatic',
            'name_en' => 'Automatic Timepieces',
            'keyword_vn' => 'dong ho co, dong ho automatic',
            'keyword_en' => 'automatic watches, mechanical timepieces',
            'description_vn' => 'Cỗ máy thời gian cơ học đỉnh cao từ các nghệ nhân chế tác Thụy Sĩ.',
            'description_en' => 'High horology mechanical movements crafted by Swiss master watchmakers.',
            'image_vn' => $img3,
            'image_en' => $img3,
            'status' => true,
            'home' => true,
            'stt' => 3,
            'parent_id' => 0,
        ]);

        // Category 4: Phiên Bản Giới Hạn
        $cateRepo->createWithAutoSlug([
            'name_vn' => 'Phiên Bản Giới Hạn',
            'name_en' => 'Limited Editions',
            'keyword_vn' => 'dong ho limited, phien ban gioi han',
            'keyword_en' => 'limited edition watches, rare timepieces',
            'description_vn' => 'Những tạo tác thời gian hiếm có, số lượng giới hạn dành cho nhà sưu tầm.',
            'description_en' => 'Rare horological works with numbered editions for prestigious collectors.',
            'image_vn' => $img4,
            'image_en' => $img4,
            'status' => true,
            'home' => true,
            'stt' => 4,
            'parent_id' => 0,
        ]);
    }
}
