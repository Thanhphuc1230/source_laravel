# tài liệu cấu trúc mã nguồn dự án (source structure & architecture guide)

> **Mục tiêu**: Tài liệu này được biên soạn và cập nhật liên tục để bất kỳ **AI Assistant (như Gemini, ChatGPT, Claude, Antigravity)** hoặc **Software Engineer** mới nào khi đọc qua cũng ngay lập tức hiểu toàn bộ kiến trúc chuẩn, luồng dữ liệu, cấu trúc Module, Service, Repository, Database Indexing và bộ Blade Component của hệ thống.

**Ngày cập nhật kiến trúc**: 2026-06-28  
**Framework**: Laravel 10.x  
**PHP Version**: >= 8.1  
**Kiến trúc cốt lõi**: Layered Architecture (Controller -> Service -> Repository -> Model) + Event-Driven Cache Invalidation + Blade Component Design System + Multi-language Accessors.

---

## 1. TỔNG QUAN HỆ THỐNG & TƯ TƯỞNG THIẾT KẾ

Hệ thống là một CMS thương mại điện tử & tin tức đa ngôn ngữ (Việt - Anh) quy mô trung bình-lớn, được thiết kế theo các nguyên lý **Clean Code**, **SOLID** và **Separation of Concerns (SoC)**:

1. **Frontend Layer (Giao diện người dùng)**:
   - **Tối ưu định tuyến**: Sử dụng duy nhất 1 Route động (`web.resolve`) cho toàn bộ đường dẫn slug (Sản phẩm, Bài viết, Danh mục, Trang tĩnh).
   - **Đa ngôn ngữ tự động (Automatic Localization)**: Models tự động nhận diện ngôn ngữ hiện tại (`app()->getLocale()`) thông qua bộ Eloquent Accessors động.
2. **Admin Layer (Quản trị hệ thống)**:
   - **Blade Component Design System**: Toàn bộ UI Admin (Bảng danh sách, Nút switch trạng thái, Ô nhập STT, Cụm action, Form đa ngôn ngữ, Upload ảnh, SEO Link Preview) đều được đóng gói thành các **Blade Components** tái sử dụng.
   - **RBAC (Role-Based Access Control)**: Phân quyền chi tiết theo Role và Permission string thông qua Middleware & Blade Directives custom (`@hasPermission`, `@hasRole`).
3. **Core Services & Data Layer**:
   - **Repository Pattern**: Tách biệt hoàn toàn các truy vấn ORM khỏi Controller.
   - **Service Layer**: Đóng gói toàn bộ Business Logic (Cart, Checkout, Slug Resolution, Search, Analytics).
   - **Event-Driven Cache Invalidation**: Tự động làm sạch bộ nhớ đệm frontend theo Tag khi Admin thay đổi dữ liệu.

---

## 2. CẤU TRÚC THƯ MỤC DỰ ÁN

```text
source_laravel/
├── app/
│   ├── Console/Commands/          # Custom Artisan Commands (sitemap, admin:create, make:featured)
│   ├── Events/                    # Domain Events khi Model thay đổi (Product, News, Cate, Page...)
│   ├── Exceptions/                # Custom Exception Handler
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Controllers Quản trị kế thừa BaseController
│   │   │   ├── Frontend/          # Controllers Giao diện người dùng (RouteController, Home, Cart...)
│   │   │   └── Auth/              # Controllers Xác thực & OTP
│   │   ├── Middleware/            # Custom Middlewares (checkAuth, RBAC, Language, Visit...)
│   │   └── Requests/Admin/        # Form Requests validation (ProductRequest, NewsRequest...)
│   ├── Listeners/                 # Event Listeners (tự động xóa cache frontend theo Tags)
│   ├── Models/                    # Eloquent Models tích hợp Cachable Trait & Multi-lang Accessors
│   ├── Providers/                 # Service Providers (App, Admin, Repository, Event, Route)
│   ├── Repositories/              # Repository Layer (Interfaces & Eloquent Implementations)
│   ├── Services/                  # Service Layer (Business logic chính)
│   └── Traits/                    # Reusable Traits (DataRemovalTrait, ImageHandlerTrait, SlugHandlerTrait)
├── config/                        # File cấu hình hệ thống (cache, lfm, mail, database...)
├── database/
│   ├── migrations/                # Schema migrations tích hợp Composite Indexes tối ưu tốc độ
│   └── seeders/                   # Database Seeders mẫu
├── public/                        # Public assets (css, js, images, vendor)
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── modules/           # Views admin theo module (product, news, analytics...)
│       │   └── partials/          # Header, Footer, Navbar, JS/CSS head
│       ├── components/admin/      # Bộ tái sử dụng chuẩn Blade Components (<x-admin.table-wrapper>...)
│       └── frontend/              # Views giao diện người dùng
├── routes/
│   ├── admin/                     # Route files chia nhỏ theo từng module quản trị
│   ├── frontend/                  # Route files frontend (home, cart, dynamic)
│   ├── web.php                    # Route aggregator chính
│   └── api.php                    # API endpoints
└── SOURCE_STRUCTURE.md            # Document kiến trúc này
```

---

## 3. CHI TIẾT CÁC LỚP NGIỆP VỤ (LAYERED ARCHITECTURE)

### 3.1 Cấu trúc Service Layer (`app/Services/`)
Service Layer là nơi tập trung toàn bộ nghiệp vụ logic của hệ thống. Tất cả câu truy vấn SQL tại đây đều tuân thủ nguyên tắc khai báo rõ ràng các cột đa ngôn ngữ (`slug_vn`, `slug_en`, `image_vn`, `image_en`, `keyword_vn`, `keyword_en`, `description_vn`, `description_en`):

| File Service | Chức năng chính |
| :--- | :--- |
| **`SlugResolutionService.php`** | Xử lý chuỗi UNION ALL ưu tiên (Page > Product > News > CateProduct > CateNews) dựa trên chỉ mục hợp phần `[status, slug_vn]` và `[status, slug_en]`. |
| **`HomeService.php`** | Tổng hợp dữ liệu Trang chủ (Sliders, Hot Products, Category News/Products, Latest News) tối ưu Eager Loading. |
| **`ProductService.php`** | Xử lý logic lọc sản phẩm, phân trang, danh mục đệ quy đa cấp và chi tiết sản phẩm. |
| **`NewsService.php`** | Xử lý danh sách tin tức theo danh mục, bài viết chi tiết và sản phẩm liên quan. |
| **`SearchService.php`** | Xử lý tìm kiếm sản phẩm đa ngôn ngữ, sắp xếp kết quả và gợi ý tìm kiếm AJAX. |
| **`CartService.php`** | Quản lý giỏ hàng lưu giữ trong Session, tính tổng tiền, thuế, khuyến mãi. |
| **`CheckoutService.php`** | Xử lý Database Transaction cho đơn hàng: Lưu Shipping -> Order Status -> Order Products -> Gửi Email thông báo. |
| **`CacheService.php`** | Wrapper quản lý Cache theo Tags (`frontend`, `products`, `news`, `categories`, `pages`). |
| **`ImageService.php`** | Xử lý Upload, Resize, Convert WebP tự động cho hình ảnh hệ thống. |
| **`DataRemovalService.php`** | Xử lý xóa an toàn bản ghi cơ sở dữ liệu kèm dọn dẹp file hình ảnh vật lý trên ổ đĩa. |

### 3.2 Cấu trúc Eloquent Models & Đa Ngôn Ngữ (`app/Models/`)
Tất cả các Model nội dung chính (**`Product`**, **`News`**, **`CateProduct`**, **`CateNew`**, **`Page`**) đều được trang bị bộ **Dynamic Accessors** thông minh:

```php
// Ví dụ mẫu trong App\Models\Product (hoặc News, CateProduct...)
public function getSlugAttribute() {
    $locale = app()->getLocale();
    return $this->{'slug_' . $locale} ?: ($this->slug_vn ?: $this->slug_en);
}

public function getNameAttribute() {
    $locale = app()->getLocale();
    return $this->{'name_' . $locale} ?: ($this->name_vn ?: $this->name_en);
}

public function getKeywordAttribute() {
    $locale = app()->getLocale();
    return $this->{'keyword_' . $locale} ?: ($this->keyword_vn ?: $this->keyword_en);
}
```
*Tác dụng*: Ở bất kỳ đâu (Views hay Services), khi gọi `$model->slug`, `$model->name`, `$model->keyword`, `$model->description`, hệ thống sẽ tự động trả về giá trị chuẩn theo ngôn ngữ hiện tại của ứng dụng.

---

## 4. BỘ THIẾT KẾ CHUẨN BLADE COMPONENTS (`resources/views/components/admin/`)

Hệ thống đã loại bỏ hoàn toàn việc viết code HTML trùng lặp hoặc gọi `@include` rời rạc ở trang quản trị, thay thế bằng hệ thống Blade Components chuẩn hóa:

| Blade Component | Cú pháp sử dụng | Công dụng |
| :--- | :--- | :--- |
| **`table-wrapper.blade.php`** | `<x-admin.table-wrapper :nameClass="$nameClass">` | Đóng gói thẻ `<form id="delete-form-all">`, bảng `<table>`, `<x-slot:header>` và `$slot` thân bảng. |
| **`table-switch.blade.php`** | `<x-admin.table-switch :uuid="$item->uuid" field="status" :value="$item->status" />` | Đóng gói nút công tắc Toggle Ajax đổi trạng thái (`status`, `home`, `hot`, `footer`). |
| **`table-stt.blade.php`** | `<x-admin.table-stt :uuid="$item->uuid" :value="$item->stt" />` | Ô nhập số thứ tự cập nhật Ajax trực tiếp trong bảng. |
| **`table-actions.blade.php`** | `<x-admin.table-actions :uuid="$item->uuid" :slug="$item->slug" ... />` | Cụm nút hành động chuẩn hóa (Xem trước SEO Link Preview, Nút Sửa, Nút Xóa). |
| **`localized-fields.blade.php`** | `<x-admin.localized-fields :fields="$fields" :model="$page" />` | Tự động sinh các ô nhập liệu đa ngôn ngữ VN / EN (Text Input, Textarea, CKEditor). |
| **`image-upload.blade.php`** | `<x-admin.image-upload locale="vn" :model="$page" imageFolder="product" />` | Khối upload ảnh kèm Preview client-side cho `image_vn` và `image_en`. |
| **`preview-link.blade.php`** | `<x-admin.preview-link :model="$page" />` | Đường dẫn xem trước trang chuẩn SEO dạng `http://domain/slug.html`. |
| **`publishing-fields.blade.php`** | `<x-admin.publishing-fields :model="$page" />` | Khối nhập Số thứ tự và Ngày đăng hỗ trợ tùy chỉnh Grid linh hoạt. |

---

## 5. TỐI ƯU HÓA TRUY VẤN & TỰ ĐỘNG LÀM SẠCH CACHE

### 5.1 Chỉ mục Hợp phần CSDL (Composite Database Indexes)
Để câu lệnh `UNION ALL` trong `SlugResolutionService` đạt tốc độ phản hồi tức thì (chỉ ~117ms bao gồm toàn bộ quá trình boot framework), 5 bảng CSDL chính (`tp_products`, `tp_news`, `tp_cate_products`, `tp_cate_news`, `tp_pages`) đã được đánh bộ chỉ mục hợp phần:
- `INDEX (status, slug_vn)`
- `INDEX (status, slug_en)`
- `INDEX (status, stt)`

### 5.2 Tự động làm sạch Cache theo Event (Cache Invalidation)
Khi Admin thực hiện bất kỳ thao tác Thêm, Sửa, Xóa hoặc Đổi trạng thái bản ghi, các Event Listeners (`ClearProductCache`, `ClearNewsCache`, `ClearCateProductCache`, `ClearCateNewCache`, `ClearPageCache`) sẽ tự động được kích hoạt để xóa sạch các tag bộ nhớ đệm liên quan:
```php
CacheService::forgetTags(['frontend', 'products', 'news', 'categories', 'pages']);
```

---

## 6. QUY TRÌNH THÊM MỘT MODULE MỚI THEO CHUẨN HỆ THỐNG

Khi cần thêm một Module quản trị mới (ví dụ: `Banner`), AI hoặc Developer cần thực hiện đúng 8 bước sau:

1. **Tạo Migration & Model**:
   - Khai báo đầy đủ các trường `uuid`, `name_vn`, `name_en`, `slug_vn`, `slug_en`, `status`, `stt`.
   - Thêm Composite Index `['status', 'slug_vn']` và `['status', 'slug_en']`.
   - Thêm các Dynamic Accessors đa ngôn ngữ trong Model.
2. **Tạo Repository Interface & Implementation**:
   - Kế thừa `BaseRepository` và đăng ký Binding trong `RepositoryServiceProvider`.
3. **Tạo Form Request**:
   - Kế thừa `BaseAdminRequest` để validate dữ liệu đầu vào.
4. **Tạo Admin Controller**:
   - Kế thừa `BaseController` để tận dụng các Trait `ImageHandlerTrait`, `SlugHandlerTrait`, `DataRemovalTrait`.
5. **Đăng ký Route Quản trị**:
   - Tạo file `routes/admin/banner.php` và include vào `routes/web.php`.
6. **Xây dựng Blade Views**:
   - Sử dụng 100% các **Blade Components** (`<x-admin.table-wrapper>`, `<x-admin.localized-fields>`...) trong `list.blade.php` và `detail.blade.php`.
7. **Đăng ký Event & Listener Clear Cache**:
   - Đảm bảo khi Model thay đổi thì Listener tự động gọi `CacheService::forgetTags(['frontend', 'banners'])`.
8. **Chạy làm sạch bộ nhớ đệm**:
   - Chạy lệnh `php artisan optimize:clear` để hệ thống ghi nhận cấu hình mới.

---

## 7. CÁC LỆNH VẬN HÀNH THƯỜNG DÙNG

```bash
# Cài đặt & khởi tạo ban đầu
composer install
php artisan key:generate
php artisan migrate:fresh --seed

# Làm sạch toàn bộ bộ nhớ đệm (View, Route, Config, Events)
php artisan optimize:clear

# Tạo tài khoản Admin mới qua Console
php artisan admin:create

# Tự động tạo file Sitemap chuẩn SEO
php artisan sitemap:generate
```

---
*Tài liệu này là chuẩn mực kiến trúc duy nhất của dự án. Mọi nâng cấp tiếp theo bắt buộc phải tuân thủ các quy tắc thiết kế đã định nghĩa ở trên.*
