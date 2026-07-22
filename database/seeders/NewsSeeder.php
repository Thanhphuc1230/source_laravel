<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $newsRepo = app(\App\Repositories\Interfaces\NewsRepositoryInterface::class);

        $articles = [
            [
                'name_vn' => 'Kinh Nghiệm Chuẩn Bị Hành Lý Du Lịch Mùa Hè Tối Giản',
                'name_en' => 'Tips for Packing a Minimalist Summer Travel Suitcase',
                'image_vn' => 'uploads/news/news_1.jpg',
                'image_en' => 'uploads/news/news_1.jpg',
                'intro_vn' => 'Những món đồ không thể thiếu và mẹo gấp quần áo gọn gàng giúp tối ưu hóa không gian vali của bạn.',
                'intro_en' => 'Must-have items and folding tricks to optimize space in your travel suitcase.',
                'content_vn' => '<p>Chuẩn bị hành lý luôn là khâu khiến nhiều người đau đầu. Hãy áp dụng quy tắc cuộn tròn quần áo thay vì gấp phẳng, mang theo các chai chiết mỹ phẩm mini và ưu tiên trang phục đa năng.</p>',
                'content_en' => '<p>Packing is always a headache. Use rolling method instead of folding, bring travel-size toiletries, and choose versatile outfits.</p>',
                'category_id' => 1,
            ],
            [
                'name_vn' => 'Top 5 Bãi Biển Đẹp Hoang Sơ Đáng Đi Nhất Việt Nam',
                'name_en' => 'Top 5 Pristine Beaches You Must Visit in Vietnam',
                'image_vn' => 'uploads/news/news_2.jpg',
                'image_en' => 'uploads/news/news_2.jpg',
                'intro_vn' => 'Khám phá những bãi cát trắng mịn, làn nước trong xanh chưa bị khai thác du lịch quá mức.',
                'intro_en' => 'Discover white sand beaches and crystal clear waters untouched by heavy tourism.',
                'content_vn' => '<p>Bên cạnh các bãi biển nổi tiếng, Việt Nam còn sở hữu nhiều hòn đảo hoang sơ tuyệt đẹp như Bãi Môn (Phú Yên), Bãi Kỳ Co (Quy Nhơn) hay các hòn đảo nhỏ tại Phú Quốc.</p>',
                'content_en' => '<p>Besides famous beaches, Vietnam owns beautiful pristine spots like Mon Beach, Ky Co Beach, or small islets around Phu Quoc.</p>',
                'category_id' => 1,
            ],
            [
                'name_vn' => 'Cẩm Nang Ăn Uống Thả Ga Khi Đi Du Lịch Đà Nẵng',
                'name_en' => 'Ultimate Street Food Guide in Da Nang City',
                'image_vn' => 'uploads/news/news_3.jpg',
                'image_en' => 'uploads/news/news_3.jpg',
                'intro_vn' => 'Điểm danh những món ăn đặc sản ngon bổ rẻ như mì Quảng, bánh tráng cuốn thịt heo, chè sầu riêng.',
                'intro_en' => 'Highlighting local dishes: Quang noodles, rolled pork rice paper, and durian sweet soup.',
                'content_vn' => '<p>Đà Nẵng không chỉ thu hút bởi danh lam thắng cảnh mà còn bởi nền ẩm thực đa dạng, giá cả phải chăng. Hãy ghé qua chợ Cồn hoặc chợ Hàn để thưởng thức trọn vẹn ẩm thực đường phố.</p>',
                'content_en' => '<p>Da Nang is famous not only for scenery but also for its rich and affordable street food culture. Make sure to visit Con Market or Han Market.</p>',
                'category_id' => 1,
            ],
            [
                'name_vn' => 'Bí Quyết Săn Vé Máy Bay Giá Rẻ Cho Kỳ Nghỉ Lễ',
                'name_en' => 'Secrets to Booking Cheap Flights for Holidays',
                'image_vn' => 'uploads/news/news_4.jpg',
                'image_en' => 'uploads/news/news_4.jpg',
                'intro_vn' => 'Thời điểm vàng để đặt vé và các công cụ so sánh giá giúp bạn tiết kiệm tối đa ngân sách chuyển đi.',
                'intro_en' => 'Golden booking window and comparison tools to help you save maximum on flight tickets.',
                'content_vn' => '<p>Để mua được vé máy bay giá tốt, bạn nên đặt trước từ 2-3 tháng, thường xuyên theo dõi các chương trình khuyến mãi đêm muộn của hãng bay và tận dụng điểm thưởng thẻ tín dụng.</p>',
                'content_en' => '<p>To get cheap flight tickets, you should book 2-3 months in advance, subscribe to airline midnight deals, and redeem credit card points.</p>',
                'category_id' => 1,
            ],
            [
                'name_vn' => 'Kinh Nghiệm Đi Cáp Treo Fansipan Không Lo Bị Độ Cao',
                'name_en' => 'Tips for Riding Fansipan Cable Car without Fear of Heights',
                'image_vn' => 'uploads/news/news_5.jpg',
                'image_en' => 'uploads/news/news_5.jpg',
                'intro_vn' => 'Cách chuẩn bị sức khỏe, trang phục giữ ấm để chinh phục Nóc nhà Đông Dương thành công.',
                'intro_en' => 'How to prepare health and warm clothing to conquer the Roof of Indochina.',
                'content_vn' => '<p>Chinh phục đỉnh Fansipan bằng cáp treo là trải nghiệm tuyệt vời tại Sa Pa. Lưu ý mặc ấm vì nhiệt độ trên đỉnh rất thấp, đi chậm để cơ thể thích nghi với áp suất không khí.</p>',
                'content_en' => '<p>Conquering Fansipan peak by cable car is a must-do in Sa Sapa. Dress warmly as the peak is cold, and walk slowly to adapt to air pressure.</p>',
                'category_id' => 1,
            ],
        ];

        foreach ($articles as $idx => $art) {
            $newsRepo->createWithAutoSlug([
                'name_vn' => $art['name_vn'],
                'name_en' => $art['name_en'],
                'image_vn' => $art['image_vn'],
                'image_en' => $art['image_en'],
                'intro_vn' => $art['intro_vn'],
                'intro_en' => $art['intro_en'],
                'content_vn' => $art['content_vn'],
                'content_en' => $art['content_en'],
                'keyword_vn' => 'tin tuc, du lich, ' . $art['name_vn'],
                'keyword_en' => 'news, travel, ' . $art['name_en'],
                'description_vn' => $art['intro_vn'],
                'description_en' => $art['intro_en'],
                'category_id' => $art['category_id'],
                'status' => true,
                'home' => true,
                'stt' => $idx + 1,
                'views' => rand(50, 500),
            ]);
        }
    }
}
