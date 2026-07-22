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
| `$website` | `System` | `tp_systems` | Thông tin hệ thống – xem mục 2 |
| `$menu` | Collection | `tp_menus` | Menu chính (parent_id=0, kèm children) |
| `$cate_product` | Collection | `tp_cate_products` | Danh mục sản phẩm cấp 1 (status=1, parent_id=0) |
| `$footer_pages` | Collection | `tp_pages` | Trang tĩnh footer (status=1, footer=1) |
| `$products_hot` | Collection | `tp_products` | Sản phẩm hot (hot=1, status=1, limit 10) |
| `$cart_count` | `int` | Session | Số lượng sản phẩm giỏ hàng (không cache) |

---

## 2. Trường của `$website` (bảng `tp_systems`)

| Trường | Blade chuẩn | Ghi chú |
|---|---|---|
| `$website->name_vn` | `{{ $website->name_vn }}` | Tên công ty tiếng Việt |
| `$website->name_en` | `{{ $website->name_en }}` | Tên công ty tiếng Anh |
| `$website->logo` | `{{ asset($website->logo) }}` | Đường dẫn tương đối trong `public/` |
| `$website->favicon` | `{{ asset('images/logo/' . $website->favicon) }}` | Favicon |
| `$website->phone` | `{{ $website->phone }}` | Hotline |
| `$website->email` | `{{ $website->email }}` | Email liên hệ |
| `$website->address` | `{{ $website->address }}` | Địa chỉ VPGD |
| `$website->map` | `src="{{ $website->map }}"` | Embed URL Google Maps iframe |
| `$website->facebook` | `href="{{ $website->facebook }}"` | Fanpage Facebook |
| `$website->youtube` | `href="{{ $website->youtube }}"` | Kênh YouTube |
| `$website->twitter` | `href="{{ $website->twitter }}"` | Twitter / X |
| `$website->instagram` | `href="{{ $website->instagram }}"` | Instagram |
| `$website->zalo` | `href="{{ $website->zalo }}"` | Zalo OA link |
| `$website->footer` | `{!! lang($website, 'footer') !!}` | HTML nội dung cột 1 footer (đa ngôn ngữ: `footer_vn` / `footer_en`) |
| `$website->meta_name` | `{{ $website->meta_name }}` | OG site_name |
| `$website->meta_keyword` | `{{ $website->meta_keyword }}` | Meta keywords trang chủ |
| `$website->meta_description` | `{{ $website->meta_description }}` | Meta description trang chủ |
| `$website->email_alert` | (dùng trong backend) | Email nhận thông báo đơn hàng mới |
| `$website->header_js` | `{!! $website->header_js !!}` | Custom JS inject vào `<head>` |
| `$website->body_js` | `{!! $website->body_js !!}` | Custom JS inject sau `<body>` |
| `$website->footer_js` | `{!! $website->footer_js !!}` | Custom JS inject trước `</body>` |

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
Không có biến riêng — **toàn bộ lấy từ `$website`** (toàn cục).

| Hiển thị | Blade |
|---|---|
| Tên công ty | `{{ $website->name_vn }}` |
| Địa chỉ | `{{ $website->address }}` |
| Điện thoại | `{{ $website->phone }}` |
| Email | `{{ $website->email }}` |
| Google Map | `src="{{ $website->map }}"` |

---

## 5. Ánh xạ Partials → Biến sử dụng

| File Partial | Biến cần dùng |
|---|---|
| `partials/head.blade.php` | `$website->favicon`, `$website->meta_name`, `@yield('module')`, `@yield('description')`, `@yield('keywords')`, `@yield('images')` |
| `partials/header.blade.php` | `$website->phone`, `$website->email`, `$website->address`, `asset($website->logo)`, `$website->name_vn`, `$cate_product`, `$menu`, `$cart_count` |
| `partials/header-mobi.blade.php` | `asset($website->logo)`, `$website->name_vn`, `$cart_count` |
| `partials/footer.blade.php` | `lang($website, 'footer')`, `$website->address`, `$website->phone`, `$website->map`, `$website->facebook`, `$website->youtube`, `$website->twitter`, `$website->name_vn`, `$footer_pages` (loop) |
| `partials/slider.blade.php` | `$sliders` → loop `$slide->image` |
| `partials/buttons.blade.php` | `$website->phone` (nút hotline/Zalo nổi) |
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

## 8. Lưu ý quan trọng

- **AutoImagePathsTrait & Đường dẫn ảnh mới**: Khi gọi `$model->image` đã là URL tuyệt đối — **không cần** bọc thêm `asset()`. Cả ảnh mới lưu theo cấu trúc phân cấp `images/{module}/{YYYY}/{MM}/{hash}.webp` và ảnh cũ lưu tên file đơn đều được tự động phân tích và hiển thị chính xác. Ngoại lệ: `$website->logo` và `$website->favicon` vẫn cần `asset()`.
- **Cache tự xóa**: Khi Admin cập nhật System → tự xóa key `website_data` và `frontend_global_data`.
- **Xóa cache thủ công**: Admin → Hệ thống → Xoá cache, hoặc `php artisan optimize:clear`.
