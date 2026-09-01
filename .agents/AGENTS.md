# AGENTS.md – Quy tắc cốt lõi dự án Base

> File này được AI tự động đọc mỗi phiên. Xem các file chi tiết trong thư mục `.agents/` để biết thêm.

---

## QUY TẮC SỐ 1 – TUYỆT ĐỐI KHÔNG HARDCODE

Khi viết blade view, **KHÔNG ĐƯỢC** ghi cứng tên công ty, SĐT, email, địa chỉ, logo, Google Map URL, link mạng xã hội.

Phải dùng biến động từ `$web`. Xem chi tiết: `.agents/frontend-data-map.md`

| Sai ❌ | Đúng ✅ |
|---|---|
| `Base` | `{{ $web->name_vn }}` |
| `0979.248.298` | `{{ $web->phone }}` |
| `contact@base.local` | `{{ $web->email }}` |
| `src="https://www.google.com/maps/embed..."` | `src="{{ $web->map }}"` |
| `<img src="/images/logo/logo.png">` | `<img src="{{ asset($web->logo) }}">` |

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

## QUY TẮC SỐ 5 – THẨM MỸ GIAO DIỆN CHUẨN UI/UX HIỆN ĐẠI (MODERN PREMIUM WEB DESIGN)

- **Định hướng thẩm mỹ:** Không sử dụng phong cách cũ kỹ. Giao diện phải đạt chuẩn Modern Premium Web: màu sắc hài hòa theo ngành hàng (Niche Palette), Typography cao cấp (Inter, Be Vietnam Pro, Plus Jakarta Sans), khoảng trắng thoáng đãng, phân cấp typography rõ ràng.
- **UI/UX & Component Details:** 
  - Card/Button có shadow nhẹ (`shadow-sm` hover thành `shadow-xl`), bo góc viền chuẩn (`rounded-xl` hoặc `rounded-2xl`).
  - Mọi button, card phải có hiệu ứng mượt `transition-all duration-300 ease-in-out`.
  - Floating action buttons đặt bên phải (`fixed bottom-5 right-5 z-50`).
- **Icon Safety (An toàn Icon):** Dùng FontAwesome v6 chuẩn syntax (bắt buộc prefix `fa-`, ví dụ: `fas fa-shopping-cart`, `fas fa-phone-alt`, `fab fa-facebook`) hoặc SVG Inline để tuyệt đối KHÔNG bị vỡ/ô vuông icon.
- **Scroll Animations:** Tích hợp hiệu ứng xuất hiện khi cuộn trang (Fade/Slide/Zoom in) bằng Intersection Observer API (`.scroll-anim.fade-up`).
- **Responsive:** Chuẩn Responsive 100%. Mobile: tin tức và sản phẩm bắt buộc hiển thị **2 cột** (`grid grid-cols-2 gap-3`).

---

## QUY TẮC SỐ 6 – KHÔNG DÙNG CDN (DOWNLOAD LOCAL ASSETS)

- Tải local toàn bộ Font ("Noto Sans", "Roboto"), Tailwind CSS (compiled), FontAwesome, và thư viện Scroll Animation (AOS / Sal.js / Intersection Observer custom) vào `public/frontend/`.
- Không nhúng CDN từ bên thứ ba.

---

## QUY TẮC SỐ 7 – TÁCH BIỆT CODE CSS & JS (NO INLINE/EMBEDDED)

* **Không viết** code CSS hoặc JS tĩnh trực tiếp trong các file Blade (thẻ `<style>`, `<script>`). Phải tổ chức chúng vào các file `.css`, `.js` chuẩn cấu trúc trong thư mục `public/`.
* **Không viết** thuộc tính CSS inline (`style="..."`) trực tiếp vào các thẻ HTML, trừ trường hợp dữ liệu đó thực sự động và được cấu hình từ database (như màu sắc, font-size cấu hình từ Admin).

---

## QUY TẮC SỐ 8 – QUY TRÌNH GIT & COMMIT CÓ KIỂM SOÁT (TUYỆT ĐỐI BẮT BUỘC)

1. **KHÔNG TỰ Ý COMMIT HAY PUSH:** AI tuyệt đối KHÔNG ĐƯỢC tự động chạy lệnh `git commit` hoặc `git push` trong bất kỳ trường hợp nào trừ khi user ra lệnh trực tiếp trong phiên chat đó.
2. **COMMIT THEO YÊU CẦU ĐƠN LẺ:** Khi user yêu cầu *"Commit code giúp tôi"*, AI CHỈ ĐƯỢC COMMIT DUY NHẤT LẦN ĐÓ cho công việc/task hiện tại.
3. **LUÔN HỎI LẠI Ở TASK TIẾP THEO:** Sau khi hoàn thành một task mới tiếp theo, AI KHÔNG ĐƯỢC tự động commit dựa trên lệnh cũ. AI phải dừng lại và hỏi user: *"Tôi đã hoàn thành task [Tên Task]. Bạn có muốn tôi commit các thay đổi này không?"*.

## QUY TẮC SỐ 9 – QUY TRÌNH CHUYỂN ĐỔI CHỦ ĐỀ/THEME (TOPIC REBUILD)

Khi người dùng yêu cầu đổi chủ đề website sang ngành hàng mới (ví dụ: Đồng hồ, Mỹ phẩm, Spa, Du lịch...):
1. BẮT BUỘC thực thi Artisan command đầu tiên: `php artisan site:rebuild --topic="<tên_topic>"` (Để hệ thống dọn sạch media rác, reset DB và seed đúng bộ dữ liệu/hình ảnh của ngành hàng đó).
2. SAU ĐÓ MỚI tiến hành tùy biến file Blade và CSS (`theme-style.css`).
3. Chạy lệnh xóa cache: `php artisan optimize:clear`.

---

## CÁC FILE THAM KHẢO CHI TIẾT

| File | Nội dung |
|---|---|
| `.agents/rules/01-architecture-boundary.md` | Ranh giới kiến trúc & quy tắc Zero-Hardcode khi làm Theme |
| `.agents/rules/02-theme-building-process.md` | Quy trình 3 bước chuẩn hóa khởi tạo & tùy biến Theme |
| `.agents/frontend-design.md` | Quy chuẩn thiết kế Frontend (Font, Tailwind, Scroll Animation, UI/UX) |
| `.agents/frontend-data-map.md` | Bảng đầy đủ biến động, partials, helper functions |
| `.agents/architecture.md` | Cấu trúc thư mục, Models, Services, Repositories |
| `.agents/new-website-workflow.md` | Quy trình tạo website mới trong 1 phiên chat |
| `.agents/memory.md` | Lịch sử quyết định kiến trúc & UI/UX dài hạn |
