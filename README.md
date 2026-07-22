# Laravel Admin System

> Hệ thống quản trị Laravel hiện đại với architecture tối ưu và UI/UX thân thiện

## 🚀 Tính năng chính

- **Quản lý Content**: Sản phẩm, tin tức, trang nội dung, slider với UX được cải thiện
- **Phân loại linh hoạt**: Danh mục sản phẩm, danh mục tin tức với cấu trúc cây
- **Xử lý Media**: Upload, resize, convert WebP tự động với ImageService
- **SEO Friendly**: Slug tự động, meta tags, sitemap generator
- **Responsive Design**: Giao diện responsive trên mọi thiết bị
- **Analytics**: Thống kê truy cập và báo cáo
- **Cache System**: Redis/File cache với performance tối ưu
- **Bulk Operations**: Xóa nhiều records với cleanup tự động
- **Admin Tools**: Custom Artisan commands cho development
- **RBAC System**: Role-Based Access Control với 61 permissions chi tiết

## 🏗️ Kiến trúc

### Backend
- **Laravel 10**: Framework PHP hiện đại
- **PHP 8.3+**: Sử dụng các tính năng mới nhất
- **MySQL**: Cơ sở dữ liệu quan hệ
- **Redis**: Cache, Session, Queue với Predis client
- **Trait-based Architecture**: Code tái sử dụng cao với 3 traits chính
- **Service Layer**: ImageService, DataRemovalService, ModelToggleService

### Frontend Admin
- **Bootstrap 5**: UI Framework
- **jQuery**: JavaScript library
- **CKEditor**: Rich text editor
- **SweetAlert**: Beautiful alerts

## 🔧 Cài đặt nhanh

```bash
# Clone repository
git clone https://github.com/Thanhphuc1230/source_laravel.git
cd source_laravel

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start server
php artisan serve
```

## 📖 Documentation

Tài liệu (docs) đã được tách sang một repository riêng. Vui lòng xem tài liệu đầy đủ tại:

📚 https://github.com/Thanhphuc1230/doc-source-laravel

Nếu bạn muốn duyệt nội dung hướng dẫn từng phần, hãy mở repo docs phía trên — tất cả các trang markdown và tài sản liên quan đã được chuyển vào đó.

## 🛠️ Development

### Recent Optimizations

- ✅ **ImageHandlerTrait**: Centralized image processing
- ✅ **DataRemovalTrait**: Centralized data removal with cleanup
- ✅ **SlugHandlerTrait**: Unique slug generation với auto-increment
- ✅ **Checkbox functionality**: Working select all across all modules
- ✅ **70% code reduction**: Eliminated duplicate code
- ✅ **Role-based Authorization**: Simplified access control system
- ✅ **Product Settings Management**: Flexible configuration system

### Traits System

```php
// ImageHandlerTrait
$data['image'] = $this->handleSingleImage($request);
$data['image_detail'] = $this->handleMultipleImages($request);

// SlugHandlerTrait  
$data['slug'] = $this->generateUniqueSlug($data['name_vn'], $this->model::class);

// DataRemovalTrait
return $this->destroyData($uuid);
return $this->destroyAllData($request);
```

## 📊 Performance

- **Image Optimization**: WebP conversion, quality optimization
- **Unique Slugs**: SEO-friendly URLs với auto-increment
- **Bulk Operations**: Efficient mass operations
- **Code Reuse**: 70% reduction in duplicate code

## 🔒 Bảo mật & Phân Quyền

### RBAC System (Role-Based Access Control)
Hệ thống phân quyền toàn diện với 61 permissions cho 13 modules:

#### Test Accounts
```bash
# Admin - Toàn quyền (61 permissions)
Email: admin@gmail.com
Password: @admin123

# Manager - Quản lý (40+ permissions)  
Email: manager@gmail.com
Password: @manager123

# Staff - Nhân viên (15+ permissions)
Email: staff@gmail.com
Password: @staff123

# Editor - Biên tập nội dung (14 permissions)
Email: editor@gmail.com
Password: @editor123
```

#### Permissions Structure
- **Product Management**: cate_product.*, product.*, product_setting.*
- **Content Management**: cate_news.*, news.*, page.*
- **Media Management**: slider.*, menu.*
- **Customer Management**: feedback.*, contact.*, comment.*
- **System Management**: user.*, role.*, permission.*, system.*
- **Analytics**: analytics.view
- **Orders**: order.*

#### Route Protection
```php
// Middleware được áp dụng tự động cho tất cả admin routes
Route::get('/', 'index')->middleware('permission:product.view');
Route::post('/', 'store')->middleware('permission:product.create');
Route::put('/{id}', 'update')->middleware('permission:product.edit');
Route::delete('/{id}', 'destroy')->middleware('permission:product.delete');
```

#### Blade Directives
```blade
@hasPermission('product.create')
    <button class="btn btn-success">Thêm mới</button>
@endhasPermission

@hasAnyPermission(['product.view', 'cate_product.view'])
    <li class="nav-item">Product Menu</li>
@endhasAnyPermission
```

### Security Features
- **Role-based Access Control**: Phân quyền theo level (Admin, Manager, Staff, Editor)
- **Route-level Protection**: Middleware bảo vệ routes nhạy cảm
- **View-level Protection**: Blade directives ẩn/hiện elements theo quyền
- **Multi-layer Security**: Bảo mật nhiều lớp từ Route → Request → Controller
- **Rate Limiting & Brute-Force Protection**: Giới hạn tối đa 5 lần thử đăng nhập sai cho mỗi cặp `{email}|{IP}` (khóa 15 phút) và giới hạn tối đa 3 lần gửi contact trong 5 phút từ mỗi IP sử dụng RateLimiter của Laravel.

## ⚡ Artisan Commands

### Custom Commands
```bash
# Tạo admin account
php artisan admin:create

# Tạo full CRUD cho feature mới  
php artisan make:featured {name}

# Generate sitemap
php artisan sitemap:generate
```

## 🏗️ Services & Traits

### Services
- **ImageService**: Xử lý upload, resize, WebP conversion
- **DataRemovalService**: Xóa data với cleanup ảnh tự động
- **ModelToggleService**: Toggle status và update order
- **RateLimitService**: Rate limiting chống brute-force cho đăng nhập admin (kết hợp email + IP) và chống spam gửi contact (dựa trên IP) sử dụng RateLimiter của Laravel.

### Traits  
- **ImageHandlerTrait**: Centralized image processing
- **DataRemovalTrait**: Standardized data removal
- **SlugHandlerTrait**: Auto slug generation với unique check

## 🧪 Testing

- **Unit Tests**: PHPUnit với comprehensive test coverage
- **Test Factories**: Model factories cho test data generation
- **Test Coverage**: 
  - ✅ CateNewController (8 tests passing)
  - ✅ RateLimitService (comprehensive service testing)
  - ✅ Database testing với RefreshDatabase trait
- **Test Environment**: Dedicated `.env.testing` configuration

## 📝 Recent Updates

### Latest Features (2024)
- ✅ **UX Improvements**: Show category name in news list
- ✅ **Performance**: Add select optimization in admin controllers
- ✅ **Dependencies**: Added Redis (Predis) support
- ✅ **Admin Panel**: Watch functionality in admin lists
- ✅ **File Manager**: Order images by time (desc)
- ✅ **SEO**: Keywords and description fields in models
- ✅ **Cache**: Event-based cache clearing system
- ✅ **Testing**: Unit tests for CateNewController and RateLimitService
- ✅ **Security**: IP-based rate limiting, enhanced form validation

## 📄 License

This project is licensed under the MIT License.

## 🤝 Contributing

Contributions are welcome! Please read the [contributing guide](https://thanhphuc1230.github.io/source_laravel/#/contributing) for details.

## 📞 Support



## 🧑‍💻 Coding Convention & Code Style

### 1. EditorConfig
Đã thiết lập file `.editorconfig` ở gốc dự án để chuẩn hóa indent, encoding, newline cho tất cả thành viên.

### 2. PHP Coding Style
Áp dụng chuẩn PSR-12 cho toàn bộ code PHP. Khuyến nghị sử dụng [Laravel Pint](https://laravel.com/docs/10.x/pint) để tự động format code.

#### Cài đặt Laravel Pint
```bash
composer require laravel/pint --dev
```

#### Format toàn bộ code
```bash
./vendor/bin/pint
```

#### Tích hợp vào quy trình làm việc
- Chạy Pint trước khi commit code.
- Có thể cấu hình thêm file `pint.json` nếu muốn tùy chỉnh rule.

### 3. Quy tắc chung
- Sử dụng indent 4 spaces cho PHP, 2 spaces cho JS/CSS.
- Đặt tên biến, hàm rõ nghĩa, tiếng Anh.
- Comment rõ ràng cho các logic phức tạp.
- Không để code thừa, code chết.
- Đảm bảo mỗi file chỉ có một trách nhiệm chính (Single Responsibility Principle).

> Tham khảo thêm: [PSR-12 Standard](https://www.php-fig.org/psr/psr-12/)

---

### 🎨 4. Taste Skill & Design Aesthetics (Quy tắc Thẩm mỹ & Giao diện Cao cấp)
Hệ thống này đề cao tính thẩm mỹ cao cấp (Premium UI/UX). Bất kỳ AI Assistant nào làm việc trên repository này đều phải tuân thủ nghiêm ngặt các quy tắc thiết kế:
* **Giao diện WOW**: Sử dụng màu sắc hài hòa (HSL/RGB tinh tế, Dark/Light Mode hiện đại), Typography sang trọng (Inter, Outfit, Chakra Petch), bo góc card và nút từ `8px` đến `16px`, sử dụng hiệu ứng bóng đổ mịn (soft shadows) và viền mờ (glassmorphism).
* **Tương tác sống động**: Tất cả các phần tử tương tác (button, link, card) bắt buộc phải có hiệu ứng hover mượt mà (`transition: all 0.3s ease-in-out`), tạo cảm giác phản hồi tức thì và sinh động.
* **Quy trình Phát triển Trọn gói (1-Command Generator)**: Khi được giao thiết kế một website/tính năng theo một chủ đề cụ thể, AI phải tự động tạo hoàn chỉnh từ cấu trúc dữ liệu, các lớp logic nghiệp vụ (Service/Repository), giao diện quản trị (sử dụng Blade Components chuẩn của dự án) cho đến giao diện frontend được thiết kế chuyên nghiệp, đậm chất thẩm mỹ phù hợp với chủ đề đó mà không cần hỏi lại từng bước.
* **Không dùng Placeholders**: Tuyệt đối không để ô trống hoặc nội dung mẫu vô nghĩa (như lorem ipsum). Hãy sử dụng dữ liệu mẫu thực tế, hình ảnh minh họa sinh động.

Tham khảo chi tiết tại bộ quy tắc kiến trúc chuẩn: [SOURCE_STRUCTURE.md](file:///d:/laragon/www/source_laravel/SOURCE_STRUCTURE.md#8-taste-skill--design-aesthetics-quy-tac-tham-my--trai-nghiem-giao-dien-cao-cap).

