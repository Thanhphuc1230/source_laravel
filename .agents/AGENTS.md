# AGENTS.md – Quy tắc cốt lõi dự án Base

> File này được AI tự động đọc mỗi phiên. Xem các file chi tiết trong thư mục `.agents/` để biết thêm.

---

## QUY TẮC SỐ 1 – TUYỆT ĐỐI KHÔNG HARDCODE

Khi viết blade view, **KHÔNG ĐƯỢC** ghi cứng tên công ty, SĐT, email, địa chỉ, logo, Google Map URL, link mạng xã hội.

Phải dùng biến động từ `$website`. Xem chi tiết: `.agents/frontend-data-map.md`

| Sai ❌ | Đúng ✅ |
|---|---|
| `Base` | `{{ $website->name_vn }}` |
| `0979.248.298` | `{{ $website->phone }}` |
| `contact@base.local` | `{{ $website->email }}` |
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

## QUY TẮC SỐ 6 – KHÔNG DÙNG CDN (DOWNLOAD ASSETS)

Khi cần sử dụng thư viện JS/CSS, Font, Icon (như FontAwesome, jQuery, Tailwind...), **PHẢI** tải tệp tin về lưu trữ cục bộ trong thư mục `public/` thay vì nhúng trực tiếp link CDN từ bên thứ ba.

---

## QUY TẮC SỐ 7 – TÁCH BIỆT CODE CSS & JS (NO INLINE/EMBEDDED)

* **Không viết** code CSS hoặc JS tĩnh trực tiếp trong các file Blade (thẻ `<style>`, `<script>`). Phải tổ chức chúng vào các file `.css`, `.js` chuẩn cấu trúc trong thư mục `public/`.
* **Không viết** thuộc tính CSS inline (`style="..."`) trực tiếp vào các thẻ HTML, trừ trường hợp dữ liệu đó thực sự động và được cấu hình từ database (như màu sắc, font-size cấu hình từ Admin).

---

## CÁC FILE THAM KHẢO CHI TIẾT

| File | Nội dung |
|---|---|
| `.agents/frontend-data-map.md` | Bảng đầy đủ biến động, partials, helper functions |
| `.agents/architecture.md` | Cấu trúc thư mục, Models, Services, Repositories |
| `.agents/new-website-workflow.md` | Quy trình tạo website mới trong 1 phiên chat |
