# Laravel Base Source & Admin System

> Hệ thống quản trị và Base Source Laravel hiện đại với kiến trúc Layered (Controller → Service → Repository → Model), UI/UX chuẩn Modern Premium Retail, tối ưu hiệu năng và quy chuẩn Zero-Hardcode.

---

## 🚀 Tính năng cốt lõi

- **Kiến trúc Layered chuẩn mực**: Phân tách rõ ràng trách nhiệm `Controller → Service → Repository → Model`, không viết query SQL rải rác trong Controller.
- **Dynamic Website Data (Tuyệt đối KHÔNG Hardcode)**: Tự động dùng biến động từ `$web` (`$web->name_vn`, `$web->phone`, `$web->email`, `$web->logo`, `$web->map`, `$web->zalo`,...).
- **Chuyển Đổi Chủ Đề Website Siêu Tốc (`site:rebuild`)**: Lệnh Artisan độc quyền `php artisan site:rebuild --topic="<tên_topic>"` tự động dọn sạch media rác, reset database, tải hình ảnh WebP chất lượng cao và seed dữ liệu chuẩn ngành nghề (ví dụ: `watches`, `travel`,...).
- **Giao Diện Light Luxury & Modern Retail**: Phong cách thiết kế hiện đại, nền sáng thoáng (`bg-[#FAFAFA]`), chữ đen tuyền tương phản cao (`#111111`), điểm nhấn Vàng Kim Gold (`#D4AF37`), thẻ sản phẩm bo tròn `rounded-2xl` viền mờ `border-slate-100` với hiệu ứng nâng khối mượt mà `hover:-translate-y-1.5 hover:shadow-2xl`.
- **Hệ Thống Nút Liên Hệ Nổi (Floating Contact Buttons)**: Xếp dọc cố định góc phải màn hình gồm: Hotline Gọi điện (Đỏ rực kèm sóng lan `animate-ping`), Chat Zalo (Xanh Zalo), và Nút Cuộn Lên Đầu Trang (Move to Top Navy đậm thông minh tự ẩn/hiện khi cuộn).
- **Hiệu Ứng Cuộn Mượt (Scroll Animations)**: Tích hợp Intersection Observer API chuẩn (`.scroll-anim.fade-up`), các section và card trượt xuất hiện êm ái khi cuộn trang.
- **Slider Responsive 2 Banner (Desktop & Mobile)**: Quản lý banner linh hoạt theo đa ngôn ngữ (`image_desktop_vn`, `image_desktop_en`, `image_mobile_vn`, `image_mobile_en`). Render thẻ HTML5 `<picture>` chuẩn responsive.
- **CKEditor 4 Full Package + File Manager 1-Click**: Tích hợp trình soạn thảo CKEditor 4 đầy đủ tính năng, chọn ảnh 1-click mở trực tiếp Laravel File Manager, hỗ trợ định dạng danh sách số/chữ (`a.b.c.`, `1.2.3.`).
- **Nói Không Với CDN (Local Assets)**: 100% thư viện JS/CSS, Fonts (`RemixIcon`, `Select2`, `Choices.js`, `Flatpickr`, `Toastify`, `HKGrotesk`) được lưu trữ cục bộ trong `public/admin/libs/` & `public/admin/fonts/`.
- **Tập Trung Asset & Tách Biệt Code**: Không viết CSS inline hay thẻ `<style>`/`<script>` tĩnh trong file Blade. Không tạo file `.js`/`.css` lẻ tẻ cho từng component nhỏ; gom tương tác vào file chuẩn `theme-style.css` và `main.js`.
- **Blade Components Admin**: Trang quản trị dùng 100% Blade Components (`<x-admin.table-wrapper>`, `<x-admin.table-switch>`, `<x-admin.localized-fields>`, `<x-admin.image-upload>`).
- **Xử lý Media Tự Động**: Tự động convert WebP, nén ảnh thông minh, dọn dẹp ảnh vật lý trong bộ nhớ khi bản ghi tương ứng bị xóa khỏi CSDL.
- **Hệ thống Phân quyền (RBAC)**: Phân quyền chi tiết theo từng module và hành động với 61 permissions cho 13 modules.
- **SEO & Multi-Language**: Hỗ trợ SEO friendly, slug tự động đa ngôn ngữ (Việt - Anh) kiểm tra duy nhất toàn hệ thống (Cross-Tables), sitemap generator.

---

## 🏗️ Cấu trúc Kiến trúc (Layered Architecture)

```text
HTTP Request
     │
     ▼
┌─────────────┐
│ Controller  │  (Điều hướng Request, gọi Service & trả về View / JSON Response)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Service   │  (Chứa toàn bộ Business Logic, xử lý Cache, Upload, WebP, Email,...)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Repository  │  (Thực hiện các thao tác Query dữ liệu từ Database)
└──────┬──────┘
       │
       ▼
┌─────────────┐
│    Model    │  (Định nghĩa Eloquent Relationship, Accessors, Mutators, Fillable)
└─────────────┘
```

---

## 📂 Cấu trúc Thư mục Dự án

```text
source_laravel/
├── .agents/                    # Bộ quy tắc & Tài liệu hướng dẫn cho AI & Dev
│   ├── AGENTS.md               # 10 Quy tắc cốt lõi của dự án Base
│   ├── architecture.md         # Quy chuẩn kiến trúc Controller - Service - Repository
│   ├── frontend-design.md      # Quy chuẩn thiết kế Frontend (Light Luxury, Tailwind, Animations)
│   ├── frontend-data-map.md    # Bản đồ biến động $web & Blade Partials
│   ├── new-website-workflow.md # Quy trình tạo website mới trong 1 phiên chat
│   └── memory.md               # Lịch sử quyết định kiến trúc & UI/UX dài hạn
├── app/
│   ├── Console/Commands/       # Artisan Commands (site:rebuild, slug:clean,...)
│   ├── Http/
│   │   ├── Controllers/Admin/  # Controller quản trị Admin
│   │   ├── Controllers/Frontend/ # Controller phía người dùng
│   │   └── Requests/Admin/     # Validation Form Requests
│   ├── Models/                 # Eloquent Models
│   ├── Repositories/           # Data Access Layer
│   ├── Services/               # Business Logic Layer (ImageService, DataRemovalService,...)
│   └── Traits/                 # Reuse Traits (AutoImagePathsTrait, ImageHandlerTrait,...)
├── config/                     # Cấu hình hệ thống (filemanager.php, lfm.php, filesystems.php,...)
├── database/
│   ├── migrations/             # Migrations chuẩn CSDL
│   └── seeders/                # Seeders khởi tạo dữ liệu mẫu theo chủ đề
├── public/
│   ├── admin/                  # Assets giao diện Admin (Css, Js, Fonts, Libs)
│   ├── frontend/               # Assets giao diện Frontend (css/theme-style.css, js/scroll-animate.js,...)
│   ├── ckeditor/               # CKEditor 4 Full Build + Custom Plugins
│   └── uploads/                # Thư mục lưu trữ media & file upload (`public/uploads/...`)
└── resources/
    └── views/
        ├── admin/              # Giao diện Admin
        │   └── components/     # Blade Components dùng chung cho Admin
        └── frontend/           # Giao diện Frontend
            ├── components/     # Blade Components người dùng (product-card.blade.php,...)
            ├── modules/        # Views theo module (home, product, news, cart, checkout, page, contact,...)
            └── partials/       # Partials (header.blade.php, footer.blade.php, contact_buttons.blade.php,...)
```

---

## 📌 10 Quy tắc Cốt lõi của Dự án

1. **Tuyệt đối KHÔNG Hardcode**: Trong Blade View bắt buộc dùng biến động `$web` (`$web->name_vn`, `$web->phone`, `$web->email`, `$web->logo`, `$web->map`, `$web->zalo`).
2. **Kiến trúc Layered**: `Controller → Service → Repository → Model`. Không viết SQL query trực tiếp trong Controller.
3. **Blade Components Admin**: Dùng 100% Blade Components cho giao diện Admin (`<x-admin.table-wrapper>`, `<x-admin.table-switch>`,...).
4. **Cache & Performance**: Sau mọi thay đổi code/config chạy `php artisan optimize:clear` hoặc bấm nút **"Xoá cache"** trong Admin.
5. **Thẩm mỹ Giao diện Premium**: Thiết kế theo chuẩn *Light Luxury & Modern Retail*, card sản phẩm tỷ lệ 1:1, hiệu ứng hover transition mượt mà, hiển thị sản phẩm/tin tức 2 cột trên di động.
6. **Không Dùng CDN (Local Assets)**: Tải toàn bộ thư viện JS/CSS, Font, Icon về lưu trữ cục bộ tại `public/admin/` và `public/frontend/`.
7. **Tách biệt Code CSS & JS**: Không viết CSS inline hay thẻ `<style>`/`<script>` tĩnh trong file Blade.
8. **Quy trình Git & Commit có Kiểm soát**: Không tự ý commit hay push; commit theo yêu cầu đơn lẻ và luôn hỏi lại ở task tiếp theo.
9. **Quy trình Chuyển đổi Chủ đề (Topic Rebuild)**: Khi đổi ngành hàng website, bắt buộc chạy Artisan command `php artisan site:rebuild --topic="<tên_topic>"` đầu tiên để dọn sạch media rác và nạp bộ dữ liệu mẫu chuẩn.
10. **Quy tắc Tập trung Asset (Không tạo file rác)**: Cấm tạo file `.js`/`.css` lẻ cho từng component nhỏ. Gom tương tác UI/UX về file chuẩn (`main.js`, `theme-style.css`), gom các nút UI nổi vào `contact_buttons.blade.php`.

---

## 🔧 Cài đặt & Khởi chạy

```bash
# 1. Clone repository
git clone https://github.com/Thanhphuc1230/source_base_laravel.git
cd source_laravel

# 2. Cài đặt Composer Dependencies
composer install

# 3. Cấu hình môi trường
cp .env.example .env
php artisan key:generate

# 4. Khởi tạo Database & Nạp bộ dữ liệu mẫu theo chủ đề (Ví dụ: Đồng hồ)
php artisan site:rebuild --topic="watches"

# 5. Xóa Cache hệ thống
php artisan optimize:clear

# 6. Khởi chạy Server
php artisan serve
```

---

## 🔄 Lệnh Quản Trị Hệ Thống Hữu Ích

```bash
# Đổi chủ đề website sang ngành hàng mới (Tự động reset DB, tải demo WebP)
php artisan site:rebuild --topic="watches"

# Làm sạch toàn bộ các slug dính đuôi thừa trong CSDL về chuẩn SEO
php artisan slug:clean

# Xóa toàn bộ cache compiled view, config, route
php artisan optimize:clear
```

---

## 🔑 Tài khoản Quản trị Mặc định (Admin Panel)

Đường dẫn đăng nhập: `/admin/login`

| Vai trò | Email | Mật khẩu | Quyền hạn |
|---|---|---|---|
| **Admin** | `admin@gmail.com` | `@admin123` | Toàn quyền hệ thống (61 permissions) |
| **Manager** | `manager@gmail.com` | `@manager123` | Quản lý nội dung & sản phẩm (40+ permissions) |
| **Editor** | `editor@gmail.com` | `@editor123` | Biên tập tin tức & trang (14 permissions) |

---

## 📄 License & Contact

- **License**: MIT License.
- **Repository**: [GitHub Source Base Laravel](https://github.com/Thanhphuc1230/source_base_laravel)
