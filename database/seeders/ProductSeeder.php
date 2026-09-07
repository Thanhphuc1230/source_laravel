<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productRepo = app(\App\Repositories\Interfaces\ProductRepositoryInterface::class);
        $downloader = app(\App\Services\DemoImageDownloaderService::class);

        $watches = [
            // Category 1: Đồng hồ Nam (Rolex, AP, Omega)
            [
                'name_vn' => 'Rolex Submariner Date 41mm Vàng Vàng 18k Mặt Đen',
                'name_en' => 'Rolex Submariner Date 41mm 18k Yellow Gold Black Dial',
                'intro_vn' => 'Tuyệt tác đồng hồ lặn biểu tượng với vỏ vàng vàng 18k nguyên khối và vành Cerachrom đen sang trọng.',
                'intro_en' => 'The ultimate reference in diving watches, crafted in solid 18k yellow gold with black Cerachrom bezel.',
                'price' => 950000000,
                'price_old' => 1050000000,
                'content_vn' => '<p>Rolex Submariner Date là biểu tượng vĩnh cửu của sự đẳng cấp và chính xác. Được chế tác từ vàng vàng 18k nguyên khối, trang bị bộ máy Calibre 3235 thế hệ mới với khả năng dự trữ năng lượng 70 giờ và khả năng chống nước 300m.</p>',
                'content_en' => '<p>The Rolex Submariner Date is an enduring icon of prestige and precision. Crafted in solid 18k yellow gold, powered by the new-generation Calibre 3235 with 70-hour power reserve and 300m water resistance.</p>',
                'keyword' => 'rolex watch gold',
                'category_id' => 1,
                'brand_id' => 1,
                'hot' => true,
            ],
            [
                'name_vn' => 'Patek Philippe Nautilus 5711/1R Vàng Hồng 18k',
                'name_en' => 'Patek Philippe Nautilus 5711/1R 18k Rose Gold',
                'intro_vn' => 'Thiết kế huyền thoại bát giác bo tròn thanh lịch bậc nhất thế giới thời gian cao cấp.',
                'intro_en' => 'The legendary rounded octagonal bezel design representing the pinnacle of luxury sports watches.',
                'price' => 2850000000,
                'price_old' => 3100000000,
                'content_vn' => '<p>Patek Philippe Nautilus 5711/1R là đỉnh cao của sự thèm muốn từ giới mộ điệu. Vỏ và dây đeo vàng hồng 18k hoàn thiện chải xước xen kẽ đánh bóng hoàn hảo cùng mặt số nâu dập nổi ngang đặc trưng.</p>',
                'content_en' => '<p>Patek Philippe Nautilus 5711/1R is the pinnacle of haute horlogerie desire. 18k rose gold case and bracelet with alternating satin and polished finishes paired with a signature horizontal embossed brown dial.</p>',
                'keyword' => 'patek philippe nautilus watch',
                'category_id' => 1,
                'brand_id' => 2,
                'hot' => true,
            ],
            [
                'name_vn' => 'Audemars Piguet Royal Oak Chronograph 41mm Thép Không Gỉ',
                'name_en' => 'Audemars Piguet Royal Oak Chronograph 41mm Stainless Steel',
                'intro_vn' => 'Biểu tượng kinh điển của Gerald Genta với mặt số xanh Grande Tapisserie hút hồn.',
                'intro_en' => 'Gerald Genta iconic masterpiece featuring the mesmerizing blue Grande Tapisserie dial.',
                'price' => 1150000000,
                'price_old' => 1250000000,
                'content_vn' => '<p>Audemars Piguet Royal Oak Chronograph mang ngôn ngữ thiết kế góc cạnh mạnh mẽ với 8 đinh ốc lục giác bằng vàng trắng. Bộ máy Chronograph tự động Calibre 4401 in-house tích hợp cơ chế Flyback chuẩn xác.</p>',
                'content_en' => '<p>Audemars Piguet Royal Oak Chronograph embodies bold architectural lines with 8 white gold hexagonal screws. In-house Calibre 4401 automatic chronograph movement with flyback function.</p>',
                'keyword' => 'audemars piguet royal oak',
                'category_id' => 1,
                'brand_id' => 3,
                'hot' => true,
            ],
            [
                'name_vn' => 'Omega Speedmaster Moonwatch Professional Co-Axial Master Chronometer',
                'name_en' => 'Omega Speedmaster Moonwatch Professional Co-Axial Master Chronometer',
                'intro_vn' => 'Huyền thoại đồng hồ đầu tiên từng chinh phục mặt trăng cùng phi hành gia NASA.',
                'intro_en' => 'The legendary timepiece that went to the Moon with NASA Apollo astronauts.',
                'price' => 215000000,
                'price_old' => 240000000,
                'content_vn' => '<p>Omega Speedmaster Moonwatch trang bị bộ máy lên cót tay Calibre 3861 với công nghệ thoát đồng trục Co-Axial và chứng nhận Master Chronometer chống từ trường lên tới 15.000 Gauss.</p>',
                'content_en' => '<p>Omega Speedmaster Moonwatch powered by manual-winding Calibre 3861 with Co-Axial escapement and Master Chronometer certification resistant to 15,000 Gauss magnetic fields.</p>',
                'keyword' => 'omega speedmaster watch',
                'category_id' => 1,
                'brand_id' => 4,
                'hot' => false,
            ],

            // Category 2: Đồng hồ Nữ (Rolex Datejust, Cartier, Hublot)
            [
                'name_vn' => 'Rolex Datejust 31 Nữ Vàng Trắng Đính Kim Cương Mặt Khảm Xà Cừ',
                'name_en' => 'Rolex Datejust 31 Women Diamond Mother of Pearl Dial',
                'intro_vn' => 'Tuyệt phẩm kiêu sa dành riêng cho phái đẹp với mặt số xà cừ tự nhiên và cọc số kim cương.',
                'intro_en' => 'A dazzling jewel for ladies featuring natural mother-of-pearl dial and sparkling diamond hour markers.',
                'price' => 480000000,
                'price_old' => 530000000,
                'content_vn' => '<p>Rolex Datejust 31 là hiện thân của vẻ đẹp vượt thời gian. Vành bezel nạm kim cương tinh xảo, kính Sapphire phóng đại Cyclops tại góc 3 giờ và dây đeo Jubilee 5 mắt duyên dáng.</p>',
                'content_en' => '<p>Rolex Datejust 31 is the epitome of classic feminine elegance. Brilliant diamond-set bezel, Cyclops magnifying lens over the date at 3 o’clock, and supple 5-link Jubilee bracelet.</p>',
                'keyword' => 'rolex datejust diamond watch',
                'category_id' => 2,
                'brand_id' => 1,
                'hot' => true,
            ],
            [
                'name_vn' => 'Cartier Santos-Dumont Nữ Vàng Hồng 18k Dây Da Cá Sấu',
                'name_en' => 'Cartier Santos-Dumont Women 18k Rose Gold Alligator Strap',
                'intro_vn' => 'Nét thanh lịch Paris hoa lệ với núm vặn đính đá Sapphire cabochon xanh thẳm.',
                'intro_en' => 'Parisian refined elegance featuring beaded crown set with a blue sapphire cabochon.',
                'price' => 320000000,
                'price_old' => 360000000,
                'content_vn' => '<p>Cartier Santos-Dumont tái hiện phong cách Art Deco quý phái với các chữ số La Mã thanh mảnh và kim thép xanh hình thanh kiếm đặc trưng từ nhà kim hoàn hoàng gia Cartier.</p>',
                'content_en' => '<p>Cartier Santos-Dumont brings aristocratic Art Deco styling with slender Roman numerals and blued-steel sword-shaped hands from the royal jeweler Cartier.</p>',
                'keyword' => 'cartier watch luxury',
                'category_id' => 2,
                'brand_id' => 6,
                'hot' => false,
            ],

            // Category 3: Đồng hồ cơ Automatic
            [
                'name_vn' => 'Hublot Big Bang Unico King Gold Ceramic 42mm',
                'name_en' => 'Hublot Big Bang Unico King Gold Ceramic 42mm',
                'intro_vn' => 'Triết lý nghệ thuật dung hợp (Art of Fusion) kết hợp chất liệu King Gold và Ceramic đen.',
                'intro_en' => 'The Art of Fusion combining exclusive 18k King Gold and high-tech satin black ceramic.',
                'price' => 780000000,
                'price_old' => 850000000,
                'content_vn' => '<p>Hublot Big Bang Unico trang bị bộ máy skeleton in-house HUB1280 với bánh xe cột (column wheel) lộ cơ hoàn hảo ở mặt trước. Dây cao su vân thể thao êm ái cùng chốt khóa bấm One-Click thông minh.</p>',
                'content_en' => '<p>Hublot Big Bang Unico powered by in-house skeleton movement HUB1280 with dial-side visible column wheel. Structured lined black rubber strap with patented One-Click quick release system.</p>',
                'keyword' => 'hublot big bang king gold',
                'category_id' => 3,
                'brand_id' => 5,
                'hot' => true,
            ],
            [
                'name_vn' => 'Rolex Cosmograph Daytona 40mm Mặt Gấu Trúc Panda',
                'name_en' => 'Rolex Cosmograph Daytona 40mm Oystersteel Panda Dial',
                'intro_vn' => 'Chiếc đồng hồ bấm giờ thể thao được săn đón nhất trong lịch sử ngành chế tác đồng hồ.',
                'intro_en' => 'The most sought-after motorsport chronograph in the history of haute horlogerie.',
                'price' => 890000000,
                'price_old' => 980000000,
                'content_vn' => '<p>Rolex Cosmograph Daytona chế tác từ thép Oystersteel siêu bền bỉ với vành Cerachrom đen khắc thang đo tốc độ tachymetric. Mặt số trắng tương phản với các vòng cung đen tạo nên diện mạo Panda trứ danh.</p>',
                'content_en' => '<p>Rolex Cosmograph Daytona in robust Oystersteel with high-tech black Cerachrom tachymetric bezel. Crisp white dial contrasted by black subdial rings creating the famous Panda configuration.</p>',
                'keyword' => 'rolex daytona panda watch',
                'category_id' => 3,
                'brand_id' => 1,
                'hot' => true,
            ],
        ];

        foreach ($watches as $idx => $item) {
            $imagePath = $downloader->fetchAndSaveImage($item['keyword'], 800, 800) ?? ('uploads/demo/watch_' . ($idx + 1) . '.webp');

            $productRepo->createWithAutoSlug([
                'name_vn' => $item['name_vn'],
                'name_en' => $item['name_en'],
                'intro_vn' => $item['intro_vn'],
                'intro_en' => $item['intro_en'],
                'price' => $item['price'],
                'price_old' => $item['price_old'],
                'content_vn' => $item['content_vn'],
                'content_en' => $item['content_en'],
                'image_vn' => $imagePath,
                'image_en' => $imagePath,
                'image_detail' => null,
                'status' => true,
                'hot' => $item['hot'],
                'stt' => $idx + 1,
                'keyword_vn' => 'dong ho cao cap, ' . $item['name_vn'],
                'keyword_en' => 'luxury watch, ' . $item['name_en'],
                'description_vn' => $item['intro_vn'],
                'description_en' => $item['intro_en'],
                'category_id' => $item['category_id'],
                'brand_id' => $item['brand_id'],
                'uuid' => Str::uuid()->toString(),
            ]);
        }
    }
}
