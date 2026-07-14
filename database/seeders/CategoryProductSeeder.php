<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CateProduct;

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

        // Category 1: Tour Trong Nước
        $cateRepo->createWithAutoSlug([
            'name_vn' => 'Tour Trong Nước',
            'name_en' => 'Domestic Tours',
            'keyword_vn' => 'tour trong nuoc, du lich viet nam',
            'keyword_en' => 'domestic tours, vietnam travel',
            'description_vn' => 'Khám phá vẻ đẹp Việt Nam qua các tour du lịch hấp dẫn.',
            'description_en' => 'Explore the beauty of Vietnam through attractive tours.',
            'image_vn' => 'tour_6.jpg',
            'image_en' => 'tour_6.jpg',
            'status' => true,
            'home' => true,
            'stt' => 1,
            'parent_id' => 0,
        ]);

        // Category 2: Tour Quốc Tế
        $cateRepo->createWithAutoSlug([
            'name_vn' => 'Tour Quốc Tế',
            'name_en' => 'International Tours',
            'keyword_vn' => 'tour quoc te, du lich nuoc ngoai',
            'keyword_en' => 'international tours, outbound tours',
            'description_vn' => 'Hành trình khám phá thế giới rộng lớn.',
            'description_en' => 'Journey to explore the wide world.',
            'image_vn' => 'tour_1.jpg',
            'image_en' => 'tour_1.jpg',
            'status' => true,
            'home' => true,
            'stt' => 2,
            'parent_id' => 0,
        ]);

        // Category 3: Tour Cao Cấp
        $cateRepo->createWithAutoSlug([
            'name_vn' => 'Tour Cao Cấp',
            'name_en' => 'Luxury Tours',
            'keyword_vn' => 'tour cao cap, nghi duong 5 sao',
            'keyword_en' => 'luxury tours, 5 star resort',
            'description_vn' => 'Trải nghiệm du lịch nghỉ dưỡng sang trọng bậc nhất.',
            'description_en' => 'Experience the most luxurious resort travel.',
            'image_vn' => 'tour_8.jpg',
            'image_en' => 'tour_8.jpg',
            'status' => true,
            'home' => true,
            'stt' => 3,
            'parent_id' => 0,
        ]);
    }
}
