# AGENTS.md – Quy tắc cốt lõi dự án Điện Máy Trường An PQ

> File này được AI tự động đọc mỗi phiên. Xem các file chi tiết trong thư mục `.agents/` để biết thêm.

---

## QUY TẮC SỐ 1 – TUYỆT ĐỐI KHÔNG HARDCODE

Khi viết blade view, **KHÔNG ĐƯỢC** ghi cứng tên công ty, SĐT, email, địa chỉ, logo, Google Map URL, link mạng xã hội.

Phải dùng biến động từ `$website`. Xem chi tiết: `.agents/frontend-data-map.md`

| Sai ❌ | Đúng ✅ |
|---|---|
| `Điện Máy Trường An PQ` | `{{ $website->name_vn }}` |
| `0979.248.298` | `{{ $website->phone }}` |
| `dienmaytruongan@gmail.com` | `{{ $website->email }}` |
| `src="https://www.google.com/maps/embed..."` | `src="{{ $website->map }}"` |
| `<img src="/images/logo/logo.png">` | `<img src="{{ asset($website->logo) }}">` |

---

## QUY TẮC SỐ 2 – KIẾN TRÚC LAYERED

```
Controller → Service → Repository → Model
```
Không viết query SQL trực tiếp trong Controller. Xem chi tiết: `.agents/architecture.md`

---

## QUY TẮC SỐ 3 – BLADE COMPONENTS ADMIN

Trang Admin dùng 100% Blade Components, không viết HTML bảng thủ công:
`<x-admin.table-wrapper>`, `<x-admin.table-switch>`, `<x-admin.localized-fields>`, `<x-admin.image-upload>`

---

## QUY TẮC SỐ 4 – CACHE

Sau mọi thay đổi code/config: `php artisan optimize:clear`
Admin có nút **"Xoá cache"** tại: Admin → Hệ thống → Xoá cache

---

## QUY TẮC SỐ 5 – THẨM MỸ GIAO DIỆN

- Dùng Google Fonts (Montserrat, Inter, Outfit) — không dùng font mặc định
- Tất cả card/button phải có `transition: all 0.3s ease` + hover effect
- Floating buttons đặt **bên phải** (`right: 20px`) tránh che nội dung
- Mobile: news và products hiển thị **2 cột** trên cùng 1 hàng

---

## CÁC FILE THAM KHẢO CHI TIẾT

| File | Nội dung |
|---|---|
| `.agents/frontend-data-map.md` | Bảng đầy đủ biến động, partials, helper functions |
| `.agents/architecture.md` | Cấu trúc thư mục, Models, Services, Repositories |
| `.agents/new-website-workflow.md` | Quy trình tạo website mới trong 1 phiên chat |
