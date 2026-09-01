# Laravel Base Source & Admin System

> Hệ thống quản trị và Base Source Laravel hiện đại với kiến trúc Layered (Controller → Service → Repository → Model), UI/UX cao cấp và tối ưu hiệu năng.

---

## 🚀 Tính năng cốt lõi

- **Kiến trúc Layered chuẩn mực**: Phân tách rõ ràng trách nhiệm `Controller → Service → Repository → Model`, không viết SQL rải rác trong Controller.
- **Dynamic Website Data (Không Hardcode)**: Tự động dùng biến động `$web` (`$web->name_vn`, `$web->phone`, `$web->email`, `$web->logo`, `$web->map`,...).
- **Slider Responsive 2 Banner (Desktop & Mobile)**: Quản lý banner linh hoạt theo đa ngôn ngữ với các trường `image_desktop_vn`, `image_desktop_en`, `image_mobile_vn`, `image_mobile_en`. Tự động render chuẩn thẻ HTML5 `<picture>` tối ưu trải nghiệm người dùng trên thiết bị di động.
- **CKEditor 4 Full Package + File Manager 1-Click**: Tích hợp trình soạn thảo CKEditor 4 đầy đủ tính năng, chọn ảnh 1-click bật trực tiếp Laravel File Manager, hỗ trợ định dạng danh sách số/chữ (`a.b.c.`, `1.2.3.`).
- **Nói Không Với CDN (Local Assets)**: 100% thư viện JS/CSS, Fonts (`RemixIcon`, `Select2`, `Choices.js`, `Flatpickr`, `Toastify`, `HKGrotesk`) được tải về và lưu trữ cục bộ trong `public/admin/libs/` & `public/admin/fonts/`.
- **Tách biệt CSS & JS (No Inline Style/Script)**: Tuân thủ quy tắc không viết CSS inline hoặc thẻ `<style>`/`<script>` trực tiếp trong file Blade.
- **Blade Components Admin**: Trang quản trị dùng 100% Blade Components (`<x-admin.table-wrapper>`, `<x-admin.table-switch>`, `<x-admin.localized-fields>`, `<x-admin.image-upload>`).
- **Xử lý Media & File Manager**: Quản lý upload tập trung tại `public/uploads/...`, tự động convert WebP và tối ưu kích thước.
- **Hệ thống Phân quyền (RBAC)**: Phân quyền chi tiết theo từng module và hành động với 61 permissions cho 13 modules.
- **SEO & Multi-Language**: Hỗ trợ SEO friendly, slug tự động đa ngôn ngữ (Việt - Anh), sitemap generator.

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
├── .agents/                    # Tài liệu hướng dẫn & Quy tắc cốt lõi cho AI & Dev
│   ├── AGENTS.md               # 7 Quy tắc cốt lõi của dự án Base
│   ├── architecture.md         # Quy chuẩn kiến trúc Controller - Service - Repository
│   ├── frontend-data-map.md    # Danh sách biến động $web & Blade Partials
│   └── new-website-workflow.md # Quy trình khởi tạo website mới
├── app/
│   ├── Http/
│   │   ├── Controllers/Admin/  # Controller quản trị Admin
│   │   └── Requests/Admin/     # Validation Requests
│   ├── Models/                 # Eloquent Models
│   ├── Repositories/           # Data Access Layer
│   ├── Services/               # Business Logic Layer
│   └── Traits/                 # Reuse Traits (AutoImagePathsTrait, ImageHandlerTrait,...)
├── config/                     # Configs hệ thống (filemanager.php, lfm.php, filesystems.php,...)
├── database/
│   ├── migrations/             # Migrations chuẩn (Cập nhật trực tiếp migration gốc)
│   └── seeders/                # Seeders khởi tạo dữ liệu mẫu kèm ảnh demo
├── public/
│   ├── admin/                  # Assets giao diện Admin (Css, Js, Fonts, Libs)
│   │   ├── fonts/              # Local Fonts (HKGrotesk, MaterialDesignIcons, RemixIcon,...)
│   │   └── libs/               # Local Vendor Libraries (Select2, Choices, Flatpickr, Toastify,...)
│   ├── ckeditor/               # CKEditor 4 Full Build + Custom Styles
│   ├── js/admin/               # Custom Admin JS (ckeditor-init.js, system-lang.js,...)
│   └── uploads/                # Thư mục lưu trữ media & file upload (`public/uploads/...`)
└── resources/
    └── views/
        ├── admin/              # Giao diện Admin
        │   └── components/     # Blade Components dùng chung cho Admin
        └── frontend/           # Giao diện Frontend người dùng
            └── partials/       # Partials (slider.blade.php, header.blade.php,...)
```

---

## 📌 7 Quy tắc Cốt lõi của Dự án

1. **Tuyệt đối KHÔNG Hardcode**: Trong Blade View bắt buộc dùng biến động `$web` (`$web->name_vn`, `$web->phone`, `$web->email`, `$web->logo`, `$web->map`).
2. **Kiến trúc Layered**: `Controller → Service → Repository → Model`. Không viết SQL query trực tiếp trong Controller.
3. **Blade Components Admin**: Dùng 100% Blade Components cho giao diện Admin (`<x-admin.table-wrapper>`, `<x-admin.table-switch>`,...).
4. **Cache & Performance**: Sau mọi thay đổi code/config chạy `php artisan optimize:clear` hoặc bấm nút **"Xoá cache"** trong Admin.
5. **Thẩm mỹ Giao diện Premium**: Sử dụng Google Fonts (Montserrat, Inter, Outfit), hiệu ứng hover transition mượt mà `all 0.3s ease`, hiển thị tin tức/sản phẩm 2 cột trên di động.
6. **Không Dùng CDN (Download Assets)**: Tải toàn bộ thư viện JS/CSS, Font, Icon về lưu trữ cục bộ tại `public/admin/libs/` và `public/admin/fonts/`.
7. **Tách biệt Code CSS & JS**: Không viết CSS inline hay thẻ `<style>`/`<script>` tĩnh trong file Blade.

---

## 🔧 Cài đặt & Khởi chạy

```bash
# 1. Clone repository
git clone https://github.com/Thanhphuc1230/source_base_laravel.git
cd source_laravel

# 2. Cài đặt Composer & NPM Packages
composer install
npm install

# 3. Cấu hình môi trường
cp .env.example .env
php artisan key:generate

# 4. Chạy Migration & Seeder dữ liệu mẫu
php artisan migrate --seed

# 5. Xóa Cache hệ thống
php artisan optimize:clear

# 6. Khởi chạy Server
php artisan serve
```

---

## 🔑 Tài khoản Thử nghiệm (Admin Panel)

| Vai trò | Email | Mật khẩu | Quyền hạn |
|---|---|---|---|
| **Admin** | `admin@gmail.com` | `@admin123` | Toàn quyền hệ thống (61 permissions) |
| **Manager** | `manager@gmail.com` | `@manager123` | Quản lý nội dung & sản phẩm (40+ permissions) |
| **Editor** | `editor@gmail.com` | `@editor123` | Biên tập tin tức & trang (14 permissions) |

---

## 📄 License & Contact

- **License**: MIT License.
- **Repository**: [GitHub Source Base Laravel](https://github.com/Thanhphuc1230/source_base_laravel)
