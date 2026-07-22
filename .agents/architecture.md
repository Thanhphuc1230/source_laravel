# Kiến trúc mã nguồn – Base

> Tài liệu kiến trúc kỹ thuật. Đọc file này khi cần hiểu cấu trúc thư mục, Models, Routes, tạo module mới, hoặc debug luồng dữ liệu.
> Kết hợp với `frontend-data-map.md` và `frontend-design.md` là đủ để viết code mà không cần đọc source. Đọc file này khi cần hiểu cấu trúc thư mục, tạo module mới, hoặc debug luồng dữ liệu.
> **Framework**: Laravel 10.x | **PHP**: >= 8.1 | **Kiến trúc**: Layered (Controller → Service → Repository → Model)

---

## 1. Luồng dữ liệu tổng quan

```
HTTP Request
  → Middleware (checkAuth, Language, Visit...)
  → Controller (nhận request, validate, gọi Service)
  → Service (business logic)
  → Repository (truy vấn DB qua Eloquent)
  → Model (AutoImagePathsTrait + Dynamic Accessors)
  → DB (MySQL)
  → View (Blade + FrontendComposer inject biến toàn cục)
```

---

## 2. Cấu trúc thư mục

```
app/
├── Console/Commands/          # Artisan commands (sitemap:generate, admin:create)
├── Events/                    # Domain Events (ProductUpdated, NewsUpdated...)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/             # Kế thừa BaseController
│   │   ├── Frontend/          # RouteController, HomeController, CartController...
│   │   └── Auth/
│   ├── Middleware/            # checkAuth, RBAC, Language, Visit
│   └── Requests/Admin/        # Form Requests (ProductRequest, NewsRequest...)
├── Listeners/                 # Tự động xóa cache khi Model thay đổi
├── Models/                    # Eloquent + AutoImagePathsTrait + Multi-lang Accessors
├── Providers/                 # AppServiceProvider, RepositoryServiceProvider, EventServiceProvider
├── Repositories/
│   ├── Interfaces/            # Interface định nghĩa contract
│   └── Eloquent/              # Implementation kế thừa BaseRepository
├── Services/                  # Business logic layer
├── Traits/                    # DataRemovalTrait, ImageHandlerTrait, SlugHandlerTrait
└── View/Composers/            # FrontendComposer (inject biến toàn cục)

resources/views/
├── admin/
│   ├── modules/               # product/, news/, slider/, brand/, system/...
│   └── partials/              # header, footer, navbar, js.blade.php
├── components/admin/          # Blade Components tái sử dụng
└── frontend/
    ├── master.blade.php
    ├── partials/              # head, header, header-mobi, footer, slider, buttons, script
    └── modules/               # home/, product/, news/, page/, contact/, search/

routes/
├── admin/                     # 1 file PHP/module
├── frontend/                  # home.php, dynamic.php, cart.php...
└── web.php                    # Aggregator include tất cả

public/
└── frontend/
    ├── css/style.css          # CSS Variables + toàn bộ styling
    └── js/                    # scripts
```

---

## 3. Service Layer (`app/Services/`)

| Service | Chức năng |
|---|---|
| `HomeService` | Tổng hợp dữ liệu trang chủ (Sliders, Products, News, Brands) |
| `SlugResolutionService` | UNION ALL resolve slug → Page / Product / News / CateProduct / CateNews |
| `ProductService` | Lọc sản phẩm, phân trang, chi tiết |
| `NewsService` | Danh sách tin tức, bài viết chi tiết |
| `SearchService` | Tìm kiếm đa ngôn ngữ + gợi ý AJAX |
| `CartService` | Quản lý giỏ hàng Session, tính tổng tiền |
| `CheckoutService` | Transaction: Shipping → Order → OrderItems → Email |
| `CacheService` | Wrapper xóa cache theo Tags |
| `ImageService` | Upload, Resize, Convert WebP |
| `DataRemovalService` | Xóa bản ghi + dọn file ảnh vật lý |
| `RateLimitService` | Giới hạn số lần đăng nhập sai (Brute-Force) và gửi Contact chống spam |

---

## 4. Models – Chuẩn dữ liệu

### AutoImagePathsTrait & Cơ chế lưu trữ ảnh Year/Month
Tất cả model có ảnh gắn trait này. Khi gọi `$model->image`, tự trả về **URL tuyệt đối đầy đủ**.

Từ tháng 07/2026, hệ thống sử dụng cấu trúc lưu trữ phân cấp thời gian kết hợp băm tên file:
`images/{module}/{YYYY}/{MM}/{filename_hash}.{extension}`

*   **Tên file**: Băm MD5 tên gốc + timestamp + random string nhằm tránh trùng lặp và an toàn I/O.
*   **Trường hợp lưu đầy đủ**:
    ```php
    // DB lưu: images/product/2026/07/8d08638ae5c736c9d4ed5454819c13a9.webp
    <img src="{{ $product->image }}">
    // → http://domain/images/product/2026/07/8d08638ae5c736c9d4ed5454819c13a9.webp (Không cần asset())
    ```
*   **Tương thích ngược (Fallback)**:
    Nếu DB chỉ lưu tên file đơn dạng `slide_1.jpg` (ví dụ từ Seeders cũ), Trait sẽ tự động map về thư mục module tương ứng: `images/{module}/slide_1.jpg`.

> **Ngoại lệ**: `$website->logo` và `$website->favicon` vẫn cần `asset()` thủ công.

### Dynamic Accessors đa ngôn ngữ
```php
$model->name  // → name_vn hoặc name_en theo app()->getLocale()
$model->slug  // → slug_vn hoặc slug_en
$model->image // → image_vn hoặc image_en
```

### Trường chuẩn cho mọi Model nội dung
```
uuid, name_vn, name_en, slug_vn, slug_en,
image_vn, image_en, intro_vn, intro_en,
content_vn, content_en, keyword_vn, keyword_en,
description_vn, description_en,
status (tinyint), stt (int), created_at, updated_at
```

### Composite Index (5 bảng chính)
```sql
INDEX (status, slug_vn)
INDEX (status, slug_en)
INDEX (status, stt)
```
Áp dụng cho: `tp_products`, `tp_news`, `tp_cate_products`, `tp_cate_news`, `tp_pages`

---

## 5. Toàn bộ Models & Bảng Database (prefix `tp_`)

| Model class | Bảng DB | Primary Key | Ghi chú |
|---|---|---|---|
| `System` | `tp_systems` | `id_system` | Thông tin website, logo, SĐT, map, mạng xã hội |
| `Menu` | `tp_menus` | `id_menu` | Menu điều hướng, có `children()` đệ quy |
| `CateProduct` | `tp_cate_products` | `id_cate_product` | Danh mục sản phẩm, có `products()`, `children()`, `parent()` |
| `Product` | `tp_products` | `id_product` | Sản phẩm, có `cate()` belongsTo CateProduct |
| `CateNew` | `tp_cate_news` | `id_cate_new` | Danh mục tin tức, có `news()` |
| `News` | `tp_news` | `id_new` | Bài viết, có `cate()` belongsTo CateNew |
| `Page` | `tp_pages` | `id_page` | Trang tĩnh, `footer=1` → hiển thị cột footer |
| `Slider` | `tp_sliders` | `id_slider` | Ảnh slider trang chủ |
| `Brand` | `tp_brands` | `id_brand` | Thương hiệu đối tác |
| `Feature` | `tp_features` | `id_feature` | Tính năng nổi bật (icon FontAwesome + tiêu đề + mô tả) |
| `Gallery` | `tp_galleries` | `id_gallery` | Thư viện ảnh |
| `Contact` | `tp_contacts` | `id_contact` | Form liên hệ đã gửi |
| `FeedBack` | `tp_feedback` | `id_feedback` | Phản hồi / đánh giá |
| `OrderShipping` | `tp_order_shipping` | `id_order_shipping` | Thông tin giao hàng đơn hàng |
| `OrderStatus` | `tp_order_status` | `id_order_status` | Trạng thái đơn hàng |
| `OrderProduct` | `tp_order_product` | `id_order_product` | Chi tiết sản phẩm trong đơn |
| `User` | `users` | `id` | Tài khoản admin, có `roles()` |
| `Role` | `tp_roles` | `id_role` | Vai trò RBAC |
| `Permission` | `tp_permissions` | `id_permission` | Quyền hạn RBAC |
| `MailConfig` | `tp_mail_configs` | - | Cấu hình SMTP |
| `MailTemplate` | `tp_mail_templates` | - | Mẫu email gửi đơn hàng |
| `Analytic` | `tp_analytics` | - | Thống kê lượt truy cập |
| `Font` | `tp_fonts` | - | Custom fonts admin |
| `ProductSetting` | `tp_product_settings` | - | Cài đặt hiển thị sản phẩm |
| `ChatSession` | `tp_chat_sessions` | - | Phiên chat hỗ trợ |
| `ChatMessage` | `tp_chat_messages` | - | Tin nhắn chat |

### Quan hệ chính:
```
CateProduct  →  hasMany → Product       (category_id → id_cate_product)
CateNew      →  hasMany → News          (category_id → id_cate_new)
Menu         →  hasMany → Menu          (parent_id → id_menu) [tự tham chiếu]
Product      →  belongsTo → CateProduct
News         →  belongsTo → CateNew
OrderStatus  →  hasMany → OrderProduct
```

---

## 6. Toàn bộ Routes

### Frontend Routes

| Route Name | URL | Controller | Ghi chú |
|---|---|---|---|
| `web.home` | `GET /` | `HomeController@home` | Trang chủ |
| `web.contact` | `GET /lien-he.html` | `ContactController@contact` | Trang liên hệ |
| `web.postContact` | `POST /gui-yeu-cau-lien-he` | `ContactController@postContact` | Gửi form liên hệ |
| `web.search` | `GET /tim-kiem.html` | `SearchController@search` | Tìm kiếm |
| `web.searchSuggestions` | `GET /search/suggestions` | `SearchController@suggestions` | Gợi ý AJAX |
| `web.cart` | `GET /cart` | `CartController@index` | Giỏ hàng |
| `web.addToCart` | `GET /add-to-cart/{uuid}/{qty?}` | `CartController@addToCart` | Thêm vào giỏ |
| `web.updateCart` | `POST /update-cart` | `CartController@updateCart` | Cập nhật giỏ |
| `web.removeItem` | `GET /remove-from-cart/{uuid}/{stt}` | `CartController@removeItem` | Xóa khỏi giỏ |
| `web.checkout` | `GET /checkout` | `CheckoutController@index` | Thanh toán |
| `web.checkoutStore` | `POST /checkout-store` | `CheckoutController@checkoutStore` | Đặt hàng |
| `web.orderSuccess` | `GET /order-success/{id}` | `CheckoutController@orderSuccess` | Đặt hàng thành công |
| `web.resolve` | `GET /{slug}.html` | `RouteController@resolve` | **Resolve động** – xử lý mọi slug |
| `lang` | `GET /lang/{locale}` | Closure | Chuyển ngôn ngữ (vn/en) |

> **Quan trọng – `web.resolve`**: Đây là route xử lý tất cả slug động (sản phẩm, bài viết, danh mục, trang tĩnh). URL format: `/{slug}.html`. `RouteController` gọi `SlugResolutionService` → UNION ALL query → trả về view tương ứng.

### Admin Routes (prefix `/admin`, middleware `checkAuth`)

| Module | Prefix | Route name prefix | Controller |
|---|---|---|---|
| Hệ thống | `/admin/system` | `admin.system.` | `SystemController` |
| Danh mục sản phẩm | `/admin/cate-product` | `admin.cate-product.` | `CateProductController` |
| Sản phẩm | `/admin/product` | `admin.product.` | `ProductController` |
| Danh mục tin tức | `/admin/cate-news` | `admin.cate-news.` | `CateNewController` |
| Tin tức | `/admin/news` | `admin.news.` | `NewsController` |
| Trang tĩnh | `/admin/page` | `admin.page.` | `PageController` |
| Slider | `/admin/slider` | `admin.slider.` | `SliderController` |
| Thương hiệu | `/admin/brand` | `admin.brand.` | `BrandController` |
| Menu | `/admin/menu` | `admin.menu.` | `MenuController` |
| Phản hồi | `/admin/feedback` | `admin.feedback.` | `FeedBackController` |
| Tính năng | `/admin/feature` | `admin.feature.` | `FeatureController` |
| Đơn hàng | `/admin/order` | `admin.order.` | `OrderController` |
| Liên hệ | `/admin/contact` | `admin.contact.` | `ContactController` |
| Người dùng | `/admin/user` | `admin.user.` | `UserController` |
| Vai trò & Quyền | `/admin/user-role` | `admin.user-role.` | `UserRoleController` |
| Thư viện ảnh | `/admin/gallery` | `admin.gallery.` | `GalleryController` |
| Cấu hình mail | `/admin/mail-config` | `admin.mail-config.` | `MailConfigController` |
| Mẫu email | `/admin/mail-template` | `admin.mail-template.` | `MailTemplateController` |
| Phân tích | `/admin/analytics` | `admin.analytics.` | `AnalyticsController` |
| **Xóa cache** | `GET /admin/system/clear-cache` | `admin.system.clearCache` | `SystemController@clearCache` |

### Admin Route CRUD chuẩn (mỗi module)
```php
Route::get('/', 'index')->name('index');              // Danh sách
Route::get('/create', 'create')->name('create');      // Form tạo mới
Route::post('/store', 'store')->name('store');        // Lưu mới
Route::get('/edit/{uuid}', 'edit')->name('edit');     // Form sửa
Route::post('/update/{uuid}', 'update')->name('update'); // Lưu sửa
Route::post('/delete', 'delete')->name('delete');     // Xóa 1
Route::post('/delete-all', 'deleteAll')->name('deleteAll'); // Xóa nhiều
Route::post('/update-stt', 'updateStt')->name('updateStt'); // Cập nhật STT AJAX
Route::post('/update-status', 'updateStatus')->name('updateStatus'); // Toggle status AJAX
```

---

## 7. Blade Components Admin (tái sử dụng 100%)

| Component | Cú pháp | Công dụng |
|---|---|---|
| `table-wrapper` | `<x-admin.table-wrapper :nameClass="$nameClass">` | Form xóa hàng loạt + bảng `<table>` |
| `table-switch` | `<x-admin.table-switch :uuid="$item->uuid" field="status" :value="$item->status" />` | Toggle trạng thái AJAX |
| `table-stt` | `<x-admin.table-stt :uuid="$item->uuid" :value="$item->stt" />` | Nhập STT inline AJAX |
| `table-actions` | `<x-admin.table-actions :uuid="$item->uuid" :slug="$item->slug" />` | Nút Sửa / Xóa / SEO Preview |
| `localized-fields` | `<x-admin.localized-fields :fields="$fields" :model="$model" />` | Form đa ngôn ngữ VN/EN |
| `image-upload` | `<x-admin.image-upload locale="vn" :model="$model" imageFolder="product" />` | Upload ảnh + Preview |
| `publishing-fields` | `<x-admin.publishing-fields :model="$model" />` | Nhập STT + Ngày đăng |

---

## 8. Cache & Event Invalidation

### Cache keys
| Key | TTL | Xóa khi nào |
|---|---|---|
| `frontend_global_data` | 120 phút | Admin sửa System / Menu / CateProduct / Page |
| `website_data` | 120 phút | Admin sửa bảng `tp_systems` |

### Tự động xóa cache
Khi Admin thêm/sửa/xóa bản ghi → Event Listeners kích hoạt:
```php
CacheService::forgetTags(['frontend', 'products', 'news', 'categories', 'pages']);
```

---

## 9. Quy trình thêm Module mới (8 bước)

```
1. Migration + Model
   → uuid, name_vn/en, slug_vn/en, image_vn/en, status, stt
   → Composite Index ['status','slug_vn'], ['status','slug_en']
   → AutoImagePathsTrait + Dynamic Accessors

2. Repository Interface + Eloquent Implementation
   → Đăng ký binding trong RepositoryServiceProvider

3. Form Request
   → Kế thừa BaseAdminRequest

4. Admin Controller
   → Kế thừa BaseController
   → Dùng ImageHandlerTrait, SlugHandlerTrait, DataRemovalTrait

5. Route Admin
   → Tạo routes/admin/module.php
   → Include vào routes/web.php

6. Admin Blade Views
   → Dùng 100% Blade Components trong list.blade.php và detail.blade.php

7. Event + Listener clear cache
   → Kích hoạt CacheService::forgetTags() khi Model thay đổi

8. php artisan optimize:clear
```

---

## 10. Lệnh vận hành thường dùng

```bash
# Cài đặt ban đầu
composer install
php artisan key:generate
php artisan migrate:fresh --seed

# Xóa tất cả cache
php artisan optimize:clear

# Tạo admin mới
php artisan admin:create

# Tạo sitemap SEO
php artisan sitemap:generate
```

---

## 11. Bảo mật & Chống Brute-Force/Spam (Rate Limiting)

Sử dụng `App\Services\RateLimitService` bọc quanh `RateLimiter` của Laravel để cấu hình:
1. **Login Admin** (tối đa 5 lần thử/15 phút):
   * Key định danh: `{email/username}|{IP}` (Ngăn chặn brute-force trên từng tài khoản mà không gây ảnh hưởng đến IP dùng chung).
   * Lỗi thử sai: Trả về thông báo kèm số lần còn lại (Ví dụ: *"Mật khẩu không đúng. Vui lòng nhập lại. Bạn còn 3 lần thử."*).
   * Block khi quá hạn: Báo lỗi *"Bạn đã nhập sai quá 5 lần. Vui lòng thử lại sau X phút."* (Dùng `RateLimiter::availableIn`).
2. **Spam Contact** (tối đa 3 lần gửi/5 phút):
   * Key định danh: `{IP}`.
   * Chặn gửi contact từ IP spam và báo thời gian block qua SweetAlert.
