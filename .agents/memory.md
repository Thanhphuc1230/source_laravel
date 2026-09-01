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
8. **Thống nhất Key Thương hiệu & Repository Pattern:** Type thương hiệu trong Menu, Request Validation và Helpers thống nhất dùng duy nhất 1 key `'brand'` (tuyệt đối không dùng `'brands'`). Trong Controller Admin, lấy danh sách thương hiệu active qua Repository tương ứng (ví dụ: `$this->productRepository->getActiveBrands()`) để giữ Controller luôn sạch và tuân thủ Layered Architecture.

---

## 🗄️ QUY TẮC BỔ SUNG CỘT CSDL (NO MIGRATION BLOAT)
- **Sửa trực tiếp Migration gốc:** Khi cần bổ sung/chỉnh sửa cột cho bảng CSDL có sẵn, hãy sửa trực tiếp file migration khởi tạo gốc (`database/migrations/...`) thay vì tạo thêm file migration mới làm phình thư mục `migrations`.
- **Tạo SQL Patch Script (`database/patch_schema.sql`):** Song song với việc bổ sung vào file migration gốc, cập nhật các câu lệnh `ALTER TABLE ...` tương ứng vào file script `database/patch_schema.sql` để có thể chạy thủ công trong MySQL khi cập nhật CSDL hiện có.

---

## 🎛️ QUY TẮC THIẾT KẾ FORM ĐA NGÔN NGỮ & MODULE TỐI GIẢN (Cập nhật T08/2026)
- **Module Một Bản Ghi (Single-Record Modules - Ví dụ: About/Giới thiệu)**:
  * Không sử dụng trang danh sách (list), thêm mới (create/store), hay xóa (destroy) để tránh làm rối quản trị viên.
  * Chỉ định nghĩa 3 routes: `index` (điều hướng), `edit` (hiển thị sửa), và `update` (lưu cập nhật).
  * Trong controller action `index()`, nếu DB trống sẽ tự động khởi tạo 1 bản ghi nháp có sẵn các dữ liệu và stats mặc định rồi redirect thẳng sang route `edit`.
- **Multi-language Tabbed Form dùng chung**:
  * Form đa ngôn ngữ phải sử dụng tab Bootstrap 5 (`Tiếng Việt` và `Tiếng Anh (EN)`) kèm biểu tượng lá cờ tương ứng (`vietnam.png`, `usa.png`) để thu gọn giao diện, tránh làm trôi dài trang.
  * Được triển khai tập trung trong partial `resources/views/admin/partials/localized-fields.blade.php` bằng cách bọc toàn bộ danh sách trường đa ngôn ngữ vào trong tab panel.
  * Mỗi lần gọi partial này bắt buộc sinh mã ngẫu nhiên `$tabSuffix = uniqid()` để tránh trùng ID tab gây xung đột JS khi nhúng nhiều lần trên cùng 1 trang.
- **Khắc phục lỗi vỡ hiển thị CKEditor trong Tab ẩn**:
  * Khi khởi tạo CKEditor 4 trong tab ẩn, editor sẽ bị lỗi vỡ giao diện (chiều rộng co về 0px hoặc mất toolbar) do Bootstrap áp dụng `display: none`.
  * Khắc phục triệt để bằng cách ghi đè class `.tab-content-localized > .tab-pane:not(.active)` trong `public/admin/css/custom.min.css`: thay `display: none` bằng cách ẩn qua toạ độ tuyệt đối (`position: absolute; left: -9999px; height: 0; overflow: hidden; opacity: 0;`). Điều này giúp container của CKEditor vẫn giữ nguyên kích thước vật lý thật khi render.
- **Lưu trữ Chỉ số thống kê động dạng JSON**:
  * Thay vì tạo hàng loạt cột cứng trong DB (`stat1_icon`, `stat1_value`...) gây cồng kềnh bảng, gom dữ liệu lưu vào 1 cột duy nhất kiểu `json` (cast `'array'` trong Model).
  * Cho phép upload tệp ảnh làm icon cho từng thống kê bằng cách truyền mảng file upload `stats_files[INDEX][icon]` song song với dữ liệu text. Controller tự động kiểm tra và lưu tệp vào thư mục `uploads/about/`.
- **Đồng bộ hóa Trait & Wrapper Methods**:
  * Khi controller sử dụng `CrudOperationsTrait` (ví dụ: `BrandController`), bắt buộc phải khai báo đầy đủ các phương thức public wrapper tương ứng (`edit`, `status`, `numericalOrder`, `destroy`, `destroyAll`) để định tuyến từ route file, tránh lỗi runtime `Method ... does not exist`.

