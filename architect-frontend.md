# TÀI LIỆU HƯỚNG DẪN & BLUEPRINT FRONTEND CHẤT LƯỢNG CAO (WOA ARCHITECTS)
Tài liệu này đóng vai trò như một **Khung thiết kế chuẩn (Blueprint)**. Bạn có thể sử dụng lại toàn bộ cấu trúc thư mục, quy tắc tổ chức mã nguồn, phong cách CSS tùy biến cao và cơ chế xử lý dữ liệu động này cho **bất kỳ chủ đề website nào** (ví dụ: Bất động sản, Thời trang, Du lịch, Mỹ phẩm, Nhà hàng...). 
Khi muốn thay đổi chủ đề, bạn chỉ cần thay đổi từ khóa/tiêu đề nội dung, điều chỉnh lại biến màu sắc trong CSS và thay thế các tệp hình ảnh tương ứng. Toàn bộ cấu trúc thư mục Blade, font chữ sang trọng, hiệu ứng đen trắng (B&W) sang màu sắc, giếng kính tròn, bố cục chia đôi (Left-Right Split) và trình chiếu ảnh đều được giữ nguyên để đảm bảo tính cao cấp và thẩm mỹ tối đa.
---
## 1. Bản Đồ Cấu Trúc Thư Mục Frontend (Blade & CSS/JS)
```text
woaarch.com/
|-- public/
|   `-- frontend/
|       |-- css/
|       |   `-- style.css                # CSS thiết kế hệ thống màu sắc, hiệu ứng B&W, vòng tròn
|       `-- images/
|           |-- logo.png                 # Logo tĩnh của thương hiệu
|           |-- map.png                  # Ảnh bản đồ nền liên hệ tĩnh
|           |-- gateway-project.jpg      # Banner Gateway hệ thống dự án
|           |-- gateway-explore.jpg      # Banner Gateway hệ thống khám phá
|           `-- gateway-contact.jpg      # Banner Gateway hệ thống liên hệ
|-- resources/
|   `-- views/
|       `-- frontend/
|           |-- master.blade.php         # Layout chính (Master Layout) điều phối chung
|           |-- partials/
|           |   |-- head.blade.php       # Thẻ head, khai báo thư viện và SEO động
|           |   |-- header.blade.php     # Thanh điều hướng trên máy tính, dropdown đa cấp
|           |   |-- header-mobi.blade.php# Thanh điều hướng trên điện thoại
|           |   |-- menumobi.blade.php   # Sidebar ngăn kéo di động trượt
|           |   |-- footer.blade.php     # Chân trang hiển thị thông tin và lightbox
|           |   |-- slider.blade.php     # Slider ảnh trượt sạch (không đè text)
|           |   `-- script.blade.php     # Kịch bản dark mode, đổi mùa, AJAX search, Lightbox
|           `-- modules/
|               |-- home/
|               |   `-- index.blade.php  # Trang chủ (Splash screen + Gateway + Danh mục nổi bật)
|               |-- product/
|               |   |-- category.blade.php # Lưới danh mục sản phẩm (sidebar lọc)
|               |   `-- detail.blade.php   # Chi tiết sản phẩm tông màu sang trọng + bảng thông số
|               |-- project/
|               |   |-- category.blade.php # Danh mục dự án (Lưới ảnh đen trắng hover phủ xanh)
|               |   `-- detail.blade.php   # Chi tiết dự án (Bên trái thông tin, bên phải ô kính tròn)
|               |-- news/
|               |   |-- category.blade.php # Danh sách bài viết khám phá
|               |   `-- detail.blade.php   # Chi tiết bài viết (Bố cục Left-Right, ô kính tròn)
|               |-- page/
|               |   `-- index.blade.php  # Trang tĩnh hiển thị nội dung giới thiệu/chính sách
|               |-- search/
|               |   `-- index.blade.php  # Kết quả tìm kiếm sản phẩm nội thất
|               |-- network/
|               |   `-- index.blade.php  # Biểu đồ mạng lưới kết nối nhân sự (Blue/Gray nodes)
|               |-- job/
|               |   `-- index.blade.php  # Bảng tin tuyển dụng dạng thẻ (Vacancies)
|               `-- contact/
|                   `-- index.blade.php  # Form liên hệ kèm bản đồ ghim tọa độ nảy pin
```
---
## 2. Hệ Thống Biến CSS Linh Hoạt (Public Theme Customization)
Tất cả màu sắc, font chữ và hiệu ứng của website đều được quản lý tập trung thông qua các **CSS Variables** trong [style.css](file:///d:/laragon/www/php-8.2/woaarch.com/public/frontend/css/style.css). 
### Quy Tắc Chuyển Đổi Chủ Đề Website:
Chỉ cần thay đổi giá trị màu sắc sơ cấp (`--primary-color`), phông chữ (`--font-heading`), và phối màu kem/nâu gỗ trong phần `:root` của file CSS để tạo giao diện cho bất kỳ ngành nghề nào:
```css
:root {
    /* 1. KHÔNG GIAN NỀN (Luxury Dark/Light) */
    --bg-dark: #121212;
    --bg-light: #fcfbfa;
    --bg-card: #1c1c1c;
    --bg-card-light: #f5f3ef;
    
    /* 2. PHỐI MÀU LUXURY (Kem, Nâu gỗ, Đất sét) */
    --color-cream: #f4efe6;
    --color-wood: #8e7054;
    --color-dark-gray: #1e1e1e;
    --color-light-gray: #e6e3df;
    
    /* 3. MÀU CHỦ ĐẠO DỰA TRÊN MÙA (Dynamic Season Overrides) */
    /* Mặc định: Xanh hoàng gia (Summer Blue) của WOA */
    --primary-color: #25408f; 
    --primary-rgb: 37, 64, 143;
    
    /* 4. PHÔNG CHỮ THỐNG NHẤT CAO CẤP (Montserrat & Inter) */
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Inter', sans-serif;
}
/* Lớp ghi đè màu sắc chủ đạo theo mùa (hoặc theo nhóm sản phẩm) */
html[data-season="spring"] {
    --primary-color: #7b9f35; /* Xanh lá mạ non */
    --primary-rgb: 123, 159, 53;
}
html[data-season="summer"] {
    --primary-color: #25408f; /* Xanh đại dương */
    --primary-rgb: 37, 64, 143;
}
html[data-season="autumn"] {
    --primary-color: #a8582b; /* Nâu cam lá phong ấm áp */
    --primary-rgb: 168, 88, 43;
}
html[data-season="winter"] {
    --primary-color: #5c7e92; /* Xám xanh băng tuyết */
    --primary-rgb: 92, 126, 146;
}
```
---
## 3. Kiến Trúc Lập Trình Blade & Cơ Chế Xử Lý Dữ Liệu Động
Để đảm bảo hiệu năng, tính năng đa ngôn ngữ (Bilingual) và tương thích SEO, toàn bộ mã nguồn tuân thủ nghiêm ngặt các tiêu chuẩn sau:
### 3.1 Khai Báo SEO Động Ở Đầu Mỗi View
Mỗi tệp view chi tiết hoặc danh mục phải khai báo đầy đủ các thẻ dữ liệu động ở trên cùng:
```php
@extends('frontend.master')
@section('module', lang($product_detail, 'name'))
@section('keywords', lang($product_detail, 'keyword'))
@section('description', lang($product_detail, 'description'))
@section('images', asset('images/product/' . lang($product_detail, 'image')))
@section('content')
```
### 3.2 Xử Lý Đa Ngôn Ngữ Qua Helper `lang()`
Tất cả các chuỗi văn bản hiển thị từ CSDL phải được bọc trong helper `lang($model, 'trường_dữ_liệu')`.
```html
<h2>{{ lang($menuParent, 'name') }}</h2>
<p>{{ lang($item, 'intro') }}</p>
```
*Cơ chế tự động:* Helper sẽ kiểm tra ngôn ngữ trong Session (`session()->get('locale')`), tự động tải trường tương ứng (ví dụ: `name_vn` hoặc `name_en`), và tự động fallback về tiếng Anh nếu trường tiếng Việt trống.
### 3.3 Hiển Thị Hình Ảnh Cố Định Nội Bộ
Không sử dụng ảnh placeholder từ các link bên ngoài hoặc thuộc tính xử lý lỗi hình ảnh (`onerror`), mà sử dụng trực tiếp tệp ảnh động hoặc tĩnh được lưu nội bộ:
```html
<img src="{{ asset('images/product/' . lang($item, 'image')) }}" alt="{{ lang($item, 'name') }}">
```
### 3.4 Hệ Thống Named Route Resolve Định Danh
Tất cả các thẻ liên kết `<a>` chuyển hướng đến trang chi tiết phải sử dụng cơ chế đặt tên route động:
```html
<a href="{{ route('web.resolve', ['slug' => $item->slug]) }}">
    {{ lang($item, 'name') }}
</a>
```

### 3.5 Đồng Bộ Menu Động từ Cơ Sở Dữ Liệu
Không sử dụng danh sách menu tĩnh (hardcode) trên Header hay Sidebar. Tất cả các thanh điều hướng bắt buộc phải được lặp xuất ra động từ cơ sở dữ liệu để đồng bộ hóa hoàn toàn với trang quản trị Admin.
Ví dụ cấu trúc lặp chuẩn cho menu:
```php
@if(isset($menu) && $menu->isNotEmpty())
    @foreach($menu as $item)
        @php
            $url = getUrlMenu($item);
            $activeClass = isActiveMenu($item);
        @endphp
        <li class="nav-item {{ $activeClass }}">
            <a class="nav-link" href="{{ $url }}">{{ lang($item, 'name') }}</a>
        </li>
    @endforeach
@endif
```

### 3.6 Đơn Giản Hóa Cấu Trúc Section (Tránh check if-else dư thừa)
Vì dữ liệu mẫu đã được cam kết gieo đầy đủ và cục bộ thông qua Seeders, hạn chế tối đa việc bao bọc toàn bộ khối giao diện (`<section>`) trong các điều kiện kiểm tra `@if` hay `@else` rườm rà không cần thiết.
- Trực tiếp kết xuất cấu trúc HTML và lặp dữ liệu sạch.
- Tránh việc viết mã dự phòng hiển thị giao diện trống hoặc ảnh placeholder giả lập khi dữ liệu đã được tải nội bộ hoàn chỉnh. Điều này giữ mã nguồn Blade gọn gàng, tăng hiệu năng biên dịch và duy trì bố cục trang web luôn đồng nhất.

---
## 4. Hướng Dẫn Tái Sử Dụng Blueprint Cho Chủ Đề Khác (Ví dụ: Web Bất Động Sản)
Khi bạn muốn chuyển đổi hệ thống này sang làm **Web Bất động sản cao cấp**, chỉ cần thực hiện 3 bước đơn giản:
1. **Sửa Tiêu Đề & Nội Dung Tĩnh**:
   - Mở `resources/views/frontend/partials/header.blade.php` và đổi thương hiệu `WOA ARCHITECTS` thành `WOA LAND / REAL ESTATE`.
   - Cập nhật thông tin trong trang chủ `home/index.blade.php` thành giới thiệu các dự án bất động sản, căn hộ và biệt thự nghỉ dưỡng.
2. **Cập Nhật Biến Màu Sắc CSS**:
   - Nếu bạn muốn chủ đề Bất động sản mang tông vàng kim sang trọng (Gold / Warm Sand), đổi biến màu chủ đạo mặc định trong `style.css`:
     ```css
     --primary-color: #d4af37; /* Màu vàng kim Metallic Gold */
     --primary-rgb: 212, 175, 55;
     ```
3. **Cập Nhật Hình Ảnh**:
   - Thay thế các hình ảnh trong `public/frontend/images/` (ảnh gateway, ảnh logo thương hiệu) thành ảnh các tòa nhà, mặt bằng đô thị.
   - Khi tạo mới hoặc cập nhật các bản ghi dự án trong trang quản trị Admin, hãy nhập thông số về: Kích thước diện tích (thay cho kích thước nội thất), Vị trí dự án (thay cho nguồn gốc xuất xứ), và Vật liệu xây dựng bàn giao (thay cho chất liệu gỗ/đá).

Khung cấu trúc mã nguồn thông minh này sẽ tự động biên dịch, đảm bảo bạn luôn có một website hoạt động ổn định, mượt mà, tải nhanh và mang tính thẩm mỹ thượng lưu vượt trội!

---

## 5. Quy Tắc Gieo Dữ Liệu Mẫu & Tải Ảnh Cục Bộ (Local Seeding & Image Downloader)
Để đảm bảo website sau khi khởi tạo có đầy đủ nội dung trực quan mà không bị phụ thuộc vào kết nối mạng bên ngoài, bắt buộc phải tuân thủ quy trình thiết lập dữ liệu mẫu sau:

### 5.1 Khởi tạo Seeders Đầy Đủ
Mỗi dự án sau khi xây dựng phải đi kèm các Seeder tương ứng để gieo dữ liệu thực tế (thay vì nội dung rác `Lorem Ipsum`):
- **SliderSeeder**: Gieo tối thiểu 3 slide ảnh lớn chất lượng cao đại diện cho chủ đề.
- **FeatureSeeder**: Gieo tối thiểu 4 giá trị cốt lõi / tính năng đặc trưng kèm theo class biểu tượng FontAwesome.
- **ProductSeeder**: Gieo tối thiểu 8 - 16 sản phẩm mẫu đầy đủ thông tin chi tiết (đối với du lịch: điểm khởi hành, ngày đi, lịch trình, giá cả, v.v.).
- **NewsSeeder**: Gieo tối thiểu 6 - 10 bài viết tin tức hoặc hướng dẫn chia sẻ hữu ích.

### 5.2 Tải Ảnh Về Local (Tuyệt đối không dùng CDN trong Database)
Không lưu trữ trực tiếp URL ảnh từ các trang CDN (như Unsplash, Picsum...) vào cơ sở dữ liệu. Thay vào đó, thực hiện quy trình tự động hóa tải ảnh:
1. **Viết Script Tải Ảnh**: Tạo một file PHP script tạm thời hoặc sử dụng lệnh hệ thống để tải tự động danh sách ảnh từ các nguồn mở chất lượng cao.
2. **Lưu Trực Tiếp Vào Thư Mục Local**:
   - Lưu ảnh slide vào `public/images/slider/` dưới dạng `slide_1.jpg`, `slide_2.jpg`...
   - Lưu ảnh tin tức vào `public/images/news/` dưới dạng `news_1.jpg`, `news_2.jpg`...
   - Lưu ảnh sản phẩm vào `public/images/product/` dưới dạng `tour_1.jpg`, `tour_2.jpg`...
3. **Cập Nhật Đường Dẫn Cục Bộ**: Trong cơ sở dữ liệu (Database), trường `image` hoặc `image_vn` chỉ lưu trữ tên tệp tin cục bộ (ví dụ: `tour_1.jpg`).
4. **Triển Khai Trong Blade Views**: Gọi ảnh đơn giản thông qua `asset('images/product/' . $model->image)` mà không cần kiểm tra điều kiện URL `http` hay `https` dự phòng.

### 5.3 Quy Tắc Cấu Hình Slider & Hiển Thị Sản Phẩm Trang Chủ
- **Slider Tinh Giản (Clean Visuals)**: Trình chiếu ảnh ở đầu trang chủ không cần chèn các khối văn bản (text captions) đè lên hình nhằm tôn vinh vẻ đẹp trực quan của ảnh phong cảnh. Slider phải hỗ trợ các hiệu ứng trượt mượt mà, tính năng tự động chuyển trang (**autoplay**) và chấm tròn chỉ hướng (**pagination**) tinh tế phía dưới.
- **Mật Độ Sản Phẩm Trang Chủ (Grid Density)**: Các vòng lặp kết xuất sản phẩm/tour ra trang chủ phải phong phú và cân đối. Bắt buộc hiển thị tối thiểu **8 sản phẩm / danh mục nổi bật** ở trang chủ (thay vì chỉ 4 hay 5 sản phẩm) để lấp đầy khung lưới giao diện và mang đến cảm giác phong phú, chuyên nghiệp cho website.

Quy trình này đảm bảo trang web có thể chạy offline hoàn hảo, tải trang siêu tốc và chuyên nghiệp ngay lập tức khi chạy lệnh cài đặt môi trường.