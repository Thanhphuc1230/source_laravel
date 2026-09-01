# 02 - Quy Trình Xây Dựng Theme / Giao Diện Mới (Theme Building Process)

> Hướng dẫn chuẩn 3 bước khi khởi tạo hoặc xây dựng một giao diện website/demo mới từ Base Source.

---

## 🔄 QUY TRÌNH 3 BƯỚC THỰC HIỆN

### 🚀 Bước 1: Khởi Tạo Dữ Liệu Demo (Rebuild Site)

Chạy Artisan Command để dọn sạch media rác và seed dữ liệu mẫu theo ngành hàng/chủ đề mong muốn:

```bash
# Cú pháp tổng quát
php artisan site:rebuild --topic="<tên_chủ_đề>"

# Ví dụ cho các ngành hàng cụ thể:
php artisan site:rebuild --topic="cosmetics"     # Mỹ phẩm / Làm đẹp
php artisan site:rebuild --topic="spa"           # Spa / Thẩm mỹ viện
php artisan site:rebuild --topic="travel"        # Du lịch / Tour
php artisan site:rebuild --topic="furniture"     # Nội thất / Kiến trúc
php artisan site:rebuild --topic="electronics"   # Thiết bị điện tử
```

> **Hệ thống tự động thực thi:**
> 1. Dọn dẹp toàn bộ file/folder rác trong `public/uploads/demo/`.
> 2. Cấu hình topic tương ứng (`config(['demo.current_topic' => $topic])`).
> 3. Tự động chạy `migrate:fresh --seed` và `optimize:clear`.

---

### 🎨 Bước 2: Cập Nhật CSS & Styling Hệ Thống

Tập trung toàn bộ cấu hình giao diện, bảng màu (Palette), biến CSS và typography tại:
📁 `public/frontend/css/theme-style.css`

- **CSS Variables & Color Palette**:
  ```css
  :root {
      --primary-color: #e11d48;    /* Màu chủ đạo theo ngành hàng */
      --secondary-color: #fda4af;  /* Màu phụ trợ */
      --accent-color: #f43f5e;     /* Màu điểm nhấn / CTA */
      --text-main: #1f2937;        /* Màu chữ chính */
      --bg-light: #fff1f2;         /* Nền sáng */
      --font-family: 'Be Vietnam Pro', 'Inter', sans-serif;
  }
  ```
- **Quy chuẩn UI/UX**:
  - Border-radius chuẩn (`rounded-xl`, `rounded-2xl`).
  - Shadow mượt mà (`box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05)`).
  - Transition hiệu ứng: `transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)`.
  - Responsive Mobile: Lưới sản phẩm & tin tức luôn hiển thị **2 cột** trên di động.

---

### 🧩 Bước 3: Tùy Biến Layout Blade Views

Tiến hành tùy biến cấu trúc HTML trong `resources/views/frontend/`:

1. **Header & Footer** (`resources/views/frontend/partials/header.blade.php`, `footer.blade.php`):
   - Đảm bảo thanh điều hướng, hotline, nút giỏ hàng và thông tin chân trang hiển thị đầy đủ từ biến `$web` và `$menu`.
2. **Components & Card** (`resources/views/frontend/components/`):
   - Thiết kế lại Card sản phẩm (`product-card.blade.php`), Card tin tức (`news-card.blade.php`).
3. **Trang chủ & Các trang chi tiết** (`resources/views/frontend/modules/`):
   - Bố cục lại các Section trên trang chủ (`home/index.blade.php`).

> ⚠️ **LƯU Ý QUAN TRỌNG:**
> - **Giữ nguyên các biến dữ liệu và vòng lặp `@foreach`**: Không được xóa hoặc đổi tên các biến dữ liệu được truyền từ Controller / ViewComposer (`$products`, `$news`, `$web`, `$slider`,...).
> - **Không viết CSS/JS inline**: Toàn bộ CSS phải viết trong file `.css`, không dùng thuộc tính `style="..."` hoặc thẻ `<style>`/`<script>` trong Blade.
