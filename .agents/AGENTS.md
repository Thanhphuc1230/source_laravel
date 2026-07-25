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

## QUY TẮC SỐ 5 – THẨM MỸ GIAO DIỆN WORDPRESS-STYLE (TAILWIND CSS)

- **Typography chuẩn hệ thống:** Bắt buộc áp dụng Font Stack WordPress-friendly:
  `font-family: "Noto Sans", Roboto, sans-serif;`
- **UI/UX chuẩn WordPress:** 
  - Giao diện thân thiện, khoảng trắng (padding/margin) thoáng đãng, phân cấp chữ rõ ràng.
  - Card/Button có shadow nhẹ (`shadow-sm` hover thành `shadow-xl`), bo góc viền chuẩn (`rounded-xl`).
  - Mọi button, card phải có hiệu ứng mượt `transition-all duration-300 ease-in-out`.
  - Floating action buttons đặt bên phải (`fixed bottom-5 right-5 z-50`).
- **Scroll Animations:** Tích hợp hiệu ứng xuất hiện khi cuộn trang (Fade/Slide/Zoom in) giống các Theme WordPress cao cấp (Flatsome, Astra, Avada).
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

---

## CÁC FILE THAM KHẢO CHI TIẾT

| File | Nội dung |
|---|---|
| `.agents/frontend-design.md` | Quy chuẩn thiết kế Frontend (Font, Tailwind, Scroll Animation, UI/UX) |
| `.agents/frontend-data-map.md` | Bảng đầy đủ biến động, partials, helper functions |
| `.agents/architecture.md` | Cấu trúc thư mục, Models, Services, Repositories |
| `.agents/new-website-workflow.md` | Quy trình tạo website mới trong 1 phiên chat |
| `.agents/memory.md` | Lịch sử quyết định kiến trúc & UI/UX dài hạn |
