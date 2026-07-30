# Bản đồ dữ liệu động Frontend – Base

> Tài liệu này mô tả toàn bộ biến động có sẵn trong mọi frontend Blade view.
> Mỗi khi viết hoặc sửa view, đọc file này để biết chính xác dùng biến nào ở đâu.

---

## 1. Dữ liệu toàn cục (MỌI view đều có – qua FrontendComposer)

**File**: `app/View/Composers/FrontendComposer.php`
**Cache**: 120 phút, key `frontend_global_data`
**Tự xóa khi**: Admin cập nhật System, Menu, CateProduct, Page

| Biến | Kiểu | Bảng DB | Mô tả |
|---|---|---|---|
| `$web` | `System` | `tp_systems` | Thông tin hệ thống – xem mục 2 |
| `$menu` | Collection | `tp_menus` | Menu chính (parent_id=0, kèm children) |
| `$cate_product` | Collection | `tp_cate_products` | Danh mục sản phẩm cấp 1 (status=1, parent_id=0) |
| `$footer_pages` | Collection | `tp_pages` | Trang tĩnh footer (status=1, footer=1) |
| `$products_hot` | Collection | `tp_products` | Sản phẩm hot (hot=1, status=1, limit 10) |
| `$product_settings` | `array` | `tp_product_settings` | Cấu hình sản phẩm (xem mục 8) |
| `$news_settings` | `array` | `news_settings` | Cấu hình tin tức (xem mục 8) |
| `$cart_count` | `int` | Session | Số lượng sản phẩm giỏ hàng (không cache) |

---

## 2. Trường của `$web` (bảng `tp_systems`)

| Trường | Blade chuẩn | Ghi chú |
|---|---|---|
| `$web->name_vn` | `{{ $web->name_vn }}` | Tên công ty tiếng Việt |
| `$web->name_en` | `{{ $web->name_en }}` | Tên công ty tiếng Anh |
| `$web->logo` | `{{ asset($web->logo) }}` | Đường dẫn tương đối trong `public/` |
| `$web->favicon` | `{{ asset('images/logo/' . $web->favicon) }}` | Favicon |
| `$web->phone` | `{{ $web->phone }}` | Hotline |
| `$web->email` | `{{ $web->email }}` | Email liên hệ |
| `$web->address` | `{{ $web->address }}` | Địa chỉ VPGD |
| `$web->map` | `src="{{ $web->map }}"` | Embed URL Google Maps iframe |
| `$web->facebook` | `href="{{ $web->facebook }}"` | Fanpage Facebook |
| `$web->youtube` | `href="{{ $web->youtube }}"` | Kênh YouTube |
| `$web->twitter` | `href="{{ $web->twitter }}"` | Twitter / X |
| `$web->instagram` | `href="{{ $web->instagram }}"` | Instagram |
| `$web->zalo` | `href="{{ $web->zalo }}"` | Zalo OA link |
| `$web->footer` | `{!! lang($web, 'footer') !!}` | HTML nội dung cột 1 footer (đa ngôn ngữ: `footer_vn` / `footer_en`) |
| `$web->meta_name` | `{{ $web->meta_name }}` | OG site_name |
| `$web->meta_keyword` | `{{ $web->meta_keyword }}` | Meta keywords trang chủ |
| `$web->meta_description` | `{{ $web->meta_description }}` | Meta description trang chủ |
| `$web->email_alert` | (dùng trong backend) | Email nhận thông báo đơn hàng mới |
| `$web->header_js` | `{!! $web->header_js !!}` | Custom JS inject vào `<head>` |
| `$web->body_js` | `{!! $web->body_js !!}` | Custom JS inject sau `<body>` |
| `$web->footer_js` | `{!! $web->footer_js !!}` | Custom JS inject trước `</body>` |

---

## 3. Dữ liệu trang chủ (`HomeService::getHomeData()`)

**File Service**: `app/Services/HomeService.php`
**Controller**: `app/Http/Controllers/Frontend/HomeController.php`

| Biến | Mô tả | Trường hay dùng |
|---|---|---|
| `$sliders` | Slider ảnh (status=1, stt asc) | `$slide->image` (auto full URL) |
| `$brands` | Thương hiệu đối tác (status=1, stt asc) | `$brand->name_vn`, `$brand->image` |
| `$category_product` | Danh mục trang chủ (home=1, eager load products) | `$cat->name`, `$cat->slug`, `$cat->products->take(8)` |
| `$hot_products` | Sản phẩm bán chạy (hot=1, limit 8) | `name`, `slug`, `image`, `price`, `price_old`, `uuid`, `cate->name` |
| `$latest_news` | 3 tin tức mới nhất (status=1) | `name`, `slug`, `image`, `intro_vn`, `cate->name`, `created_at` |

---

## 4. Dữ liệu theo từng trang

### Trang sản phẩm chi tiết
**Controller**: `app/Http/Controllers/Frontend/ProductController.php`

| Biến | Mô tả |
|---|---|
| `$product_detail` | Object sản phẩm: `name`, `slug`, `image`, `price`, `price_old`, `intro_vn`, `content_vn`, `keyword`, `description`, `uuid`, `cate->name`, `cate->slug` |
| `$related_products` | Collection sản phẩm liên quan cùng danh mục |

### Trang tin tức
| Biến | Mô tả |
|---|---|
| `$news_list` | Danh sách bài viết (có phân trang) |
| `$cate` | Thông tin danh mục tin tức hiện tại |
| `$news_detail` | Object bài viết chi tiết |
| `$related_news` | Collection bài viết liên quan |

### Trang liên hệ
Không có biến riêng — **toàn bộ lấy từ `$web`** (toàn cục).

| Hiển thị | Blade |
|---|---|
| Tên công ty | `{{ $web->name_vn }}` |
| Địa chỉ | `{{ $web->address }}` |
| Điện thoại | `{{ $web->phone }}` |
| Email | `{{ $web->email }}` |
| Google Map | `src="{{ $web->map }}"` |

---

## 5. Ánh xạ Partials → Biến sử dụng

| File Partial | Biến cần dùng |
|---|---|
| `partials/head.blade.php` | `$web->favicon`, `$web->meta_name`, `@yield('module')`, `@yield('description')`, `@yield('keywords')`, `@yield('images')` |
| `partials/header.blade.php` | `$web->phone`, `$web->email`, `$web->address`, `asset($web->logo)`, `$web->name_vn`, `$cate_product`, `$menu`, `$cart_count` |
| `partials/header-mobi.blade.php` | `asset($web->logo)`, `$web->name_vn`, `$cart_count` |
| `partials/footer.blade.php` | `lang($web, 'footer')`, `$web->address`, `$web->phone`, `$web->map`, `$web->facebook`, `$web->youtube`, `$web->twitter`, `$web->name_vn`, `$footer_pages` (loop) |
| `partials/slider.blade.php` | `$sliders` → loop `$slide->image` |
| `partials/buttons.blade.php` | `$web->phone` (nút hotline/Zalo nổi) |
| `partials/script.blade.php` | Không cần biến — chứa JS slider autoplay, AJAX cart, back-to-top |

---

## 6. Helper Functions

| Helper | Cú pháp | Mô tả |
|---|---|---|
| `lang()` | `{{ lang($model, 'name') }}` | Trả về `name_vn` hoặc `name_en` theo locale hiện tại |
| `isActiveMenu()` | `class="{{ isActiveMenu($item) }}"` | Trả về class CSS `active` nếu URL khớp menu |
| `getUrlMenu()` | `href="{{ getUrlMenu($item) }}"` | URL menu item (tự xử lý type: route/page/slug/external) |

---

## 7. Template SEO chuẩn đầu mỗi view

```blade
@extends('frontend.master')
@section('module', lang($model, 'name'))
@section('keywords', lang($model, 'keyword'))
@section('description', lang($model, 'description'))
@section('images', $model->image)
```

---

## 8. Cấu hình Cài đặt Sản Phẩm (`$product_settings`) & Tin Tức (`$news_settings`)

Toàn bộ được inject tự động qua `FrontendComposer` vào mọi view frontend.

### Cấu trúc mảng `$product_settings` & `$news_settings`:
| Key | Loại | Mô tả | Cách sử dụng chuẩn ở Blade |
|---|---|---|---|
| `font_size` | `string` | Cỡ chữ tiêu đề (vd: `16px`) | `style="font-size: {{ $settings['font_size'] }}"` |
| `show_intro` | `bool` | Hiển thị đoạn giới thiệu ngắn | `@if($settings['show_intro']) ... @endif` |
| `click_image_detail` | `bool` | Cho bọc link xem chi tiết vào ảnh | `@if($settings['click_image_detail']) <a href="..."> <img ...> </a> @endif` |
| `title_color` | `string` | Màu tiêu đề Hex (vd: `#064e3b`) | `style="color: {{ $settings['title_color'] }}"` |
| `category_color` | `string` | Màu danh mục Hex (vd: `#b45309`) | `style="color: {{ $settings['category_color'] }}"` |
| `banner_category` | `string` | Đường dẫn tương đối banner danh mục | `@if(!empty($settings['banner_category'])) url('{{ asset($settings['banner_category']) }}') @endif` |
| `banner_detail` | `string` | Đường dẫn tương đối banner chi tiết | `@if(!empty($settings['banner_detail'])) url('{{ asset($settings['banner_detail']) }}') @endif` |

> **QUY TẮC VIẾT CODE BLADE**:
> Không viết các khối xử lý logic `@php ... @endphp` trong các file Blade view. Blade chỉ đóng vai trò hiển thị UI và dùng câu lệnh rẽ nhánh điều kiện chuẩn `@if(!empty($settings['banner_category']))`.

---

## 9. Lưu ý quan trọng

- **AutoImagePathsTrait & Đường dẫn ảnh mới**: Khi gọi `$model->image` đã là URL tuyệt đối — **không cần** bọc thêm `asset()`. Cả ảnh mới lưu theo cấu trúc phân cấp `images/{module}/{YYYY}/{MM}/{hash}.webp` và ảnh cũ lưu tên file đơn đều được tự động phân tích và hiển thị chính xác. Ngoại lệ: `$web->logo` và `$web->favicon` vẫn cần `asset()`.
- **Cache tự xóa**: Khi Admin cập nhật System → tự xóa key `website_data` và `frontend_global_data`.
- **Xóa cache thủ công**: Admin → Hệ thống → Xoá cache, hoặc `php artisan optimize:clear`.
