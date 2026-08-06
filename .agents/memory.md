# Lịch Sử Quyết Định Kiến Trúc & UI/UX (Long-term Memory)

> File này lưu các quyết định quan trọng đã chốt. AI không được tự ý đề xuất phương án ngược lại.

---

## 🎨 UI/UX & STYLING (Cập nhật chuẩn Modern Web 2026)
- **Framework CSS:** Tailwind CSS (compile file local hoặc Play CDN cấu hình chỉn chu).
- **Typography:** Bắt buộc dùng font hiện đại Tiếng Việt: `Inter`, `Be Vietnam Pro`, hoặc `Plus Jakarta Sans`.
- **Phong cách:** Modern Premium Web Design (Thân thiện, bo tròn `rounded-xl`/`2xl`, shadow mượt, hover scale nhẹ 1.03-1.05, phân mảng phối màu Niche Color Palette theo ngành nghề).
- **Icon Safety:** Tuyệt đối ghi đúng class FontAwesome (`fas fa-...`, `fab fa-...`) hoặc SVG Inline để tránh lỗi ô vuông.
- **Animation:** Dùng Intersection Observer JS (`.scroll-anim.fade-up`) tạo hiệu ứng cuộn mượt.
- **Mobile Grid:** Sản phẩm & Tin tức luôn chia 2 cột trên Mobile (`grid-cols-2`).

---

## ⚙️ GIT & COMMIT CONTROL (QUY TẮC SỐ 8)
- KHÔNG BAO GIỜ tự động commit/push code nếu không có yêu cầu trực tiếp từ user trong phiên chat.
- Mỗi lệnh commit từ user chỉ có giá trị cho LẦN ĐÓ cho task hiện tại.
- Mọi task tiếp theo bắt buộc phải HỎI XIN PHÉP user trước khi commit (`"Tôi đã hoàn thành task [Tên Task]. Bạn có muốn tôi commit các thay đổi này không?"`).

---

## 🔗 QUY TẮC KHỞI TẠO SLUG & DỌN DẸP DATABASE (Chốt T08/2026)
- **Slug sạch nguyên bản:** Slug khi khởi tạo/cập nhật BẮT BUỘC là chuỗi sạch `Str::slug($name)`. TUYỆT ĐỐI KHÔNG cưỡng ép nối đuôi ID hoặc `-0` (`$slug = $baseSlug . '-' . $id`).
- **Kiểm tra Duy nhất Toàn hệ thống (Global Cross-Tables):** Đảm bảo tính duy nhất qua cả 5 bảng nội dung (`tp_pages`, `tp_products`, `tp_news`, `tp_cate_products`, `tp_cate_news`). CHỈ KHI phát hiện trùng tên với bản ghi khác mới nối thêm hậu tố số tăng dần (`-1`, `-2`, `-3`...).
- **Lệnh dọn dẹp:** Dùng lệnh `php artisan slug:clean` để tự động quét & đưa các slug dính đuôi thừa trong CSDL về bản chuẩn SEO nguyên bản.

---

## ⚠️ 7 LỖI THƯỜNG GẶP CẦN TUYỆT ĐỐI TRÁNH (LESSONS LEARNED)
1. **Header động 100%:** Luôn dùng `@foreach($menu as $item)` với `getUrlMenu($item)` & `isActiveMenu($item)`. Không bao giờ hardcode HTML link tĩnh trong header.
2. **Blade hoàn toàn sạch (Clean Views):** Không dùng khối `@php ... @endphp` trong Blade view. Chuyển 100% logic query/xử lý sang Service, Composer, hoặc Helper.
3. **Không bọc `asset()` với `AutoImagePathsTrait`:** Thẻ ảnh dùng `$model->image` chỉ viết `<img src="{{ $model->image }}">` (đã là URL tuyệt đối). Không bọc `asset($model->image)` để tránh lặp URL.
4. **An toàn Icon (Icon Safety):** Dùng chuẩn FontAwesome v6 (`fas fa-...`) hoặc SVG Inline. Không áp bộ lọc CSS `brightness-0 invert` lên SVG làm mất/rỗng icon.
5. **Đồng bộ Request Rules & Helpers:** Kiểm tra kỹ Validation Request (`MenuRequest`) và Helper (`MenuHelper`) đảm bảo đồng bộ 100% các key/type (ví dụ `brand` vs `brands`).
6. **Tối ưu Cache & Clear Cache Event:** Bọc `Cache::remember` cho `FrontendComposer` & Services (giữ query ở 0-2 queries khi warm). Luôn đăng ký Observer `saved`/`deleted` cho 100% Models trong `AppServiceProvider` để tự xóa cache khi Admin sửa dữ liệu.
7. **Mobile Responsive Standard:** Breadcrumb/Nav trên mobile bắt buộc có `whitespace-nowrap overflow-x-auto`. Card sản phẩm/tin tức trên mobile bắt buộc dạng **2 cột** (`grid-cols-2 gap-3`) với `truncate` chống tràn text.

---

## 🗄️ QUY TẮC BỔ SUNG CỘT CSDL (NO MIGRATION BLOAT)
- **Sửa trực tiếp Migration gốc:** Khi cần bổ sung/chỉnh sửa cột cho bảng CSDL có sẵn, hãy sửa trực tiếp file migration khởi tạo gốc (`database/migrations/...`) thay vì tạo thêm file migration mới làm phình thư mục `migrations`.
- **Tạo SQL Patch Script (`database/patch_schema.sql`):** Song song với việc bổ sung vào file migration gốc, cập nhật các câu lệnh `ALTER TABLE ...` tương ứng vào file script `database/patch_schema.sql` để có thể chạy thủ công trong MySQL khi cập nhật CSDL hiện có.
