# 01 - Quy Tắc Ranh Giới Kiến Trúc (Architecture Boundary Rules)

> Quy định nghiêm ngặt về phạm vi can thiệp code khi xây dựng hoặc tùy biến Theme/Giao diện cho website.

---

## 🚫 1. DANH SÁCH CẤM (STRICTLY FORBIDDEN)

Tuyệt đối **KHÔNG ĐƯỢC** thêm, sửa hoặc xóa bất kỳ file nào trong các thư mục Core Backend sau:

- `app/Http/Controllers/` (Tất cả Controller)
- `app/Services/` (Tất cả Business Logic Services)
- `app/Repositories/` (Tất cả Database Repositories & Interfaces)
- `app/Models/` (Tất cả Eloquent Models)
- `routes/` (Tất cả file định tuyến web, admin, auth, api)
- `database/migrations/` (Tất cả Database Migrations)

> **Lý do**: Hệ thống Core đã được chuẩn hóa theo kiến trúc Layered. Mọi can thiệp vào tầng Backend khi làm Theme sẽ phá vỡ tính tương thích và cấu trúc Base.

---

## ✅ 2. DANH SÁCH ĐƯỢC PHÉP (ALLOWED SCOPE)

Khi xây dựng hoặc tùy biến giao diện mới, **CHỈ ĐƯỢC PHÉP** thao tác trên 2 thư mục:

1. **`resources/views/frontend/`**:
   - `layouts/`, `master.blade.php` (Khung giao diện chính)
   - `partials/` (Header, Footer, Slider, Floating buttons...)
   - `components/` (Card sản phẩm, Card tin tức, Breadcrumbs...)
   - `modules/` (Giao diện trang chủ, chi tiết sản phẩm, tin tức, giỏ hàng, liên hệ...)

2. **`public/frontend/`**:
   - `css/` (Stylesheet, Theme CSS, Font CSS)
   - `js/` (Script tương tác UI, Animation)
   - `images/`, `fonts/` (Asset tĩnh cục bộ)

---

## 💎 3. QUY TẮC "ZERO-HARDCODE" (DỮ LIỆU ĐỘNG 100%)

Mọi thông tin trên Blade View **BẮT BUỘC** phải sử dụng dữ liệu động từ `FrontendComposer` và Controller truyền xuống:

| Thông tin | Cú pháp đúng ✅ | Không dùng ❌ |
|---|---|---|
| Tên website / Công ty | `{{ $web->name_vn }}` | `Công ty ABC` |
| Hotline / SĐT | `{{ $web->phone }}` / `{{ $web->hotline }}` | `0901234567` |
| Email liên hệ | `{{ $web->email }}` | `contact@domain.com` |
| Địa chỉ | `{{ $web->address_vn }}` | `123 Đường XYZ...` |
| Bản đồ Google Map | `src="{{ $web->map }}"` | `src="https://www.google.com/maps/..."` |
| Logo / Favicon | `{{ asset($web->logo) }}` | `src="/images/logo.png"` |
| Menu đa cấp | `@foreach($menu as $item)...` | Viết cứng thẻ `<li><a href="/...">` |
| Danh sách sản phẩm | `@foreach($products as $product)...` | HTML card tĩnh |
