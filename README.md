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

📚 **[Xem Documentation đầy đủ](https://thanhphuc1230.github.io/source_laravel)**

- [Cài đặt chi tiết](https://thanhphuc1230.github.io/source_laravel/#/installation)
- [Architecture Overview](https://thanhphuc1230.github.io/source_laravel/#/architecture)
- [Traits System](https://thanhphuc1230.github.io/source_laravel/#/traits)
- [Hướng dẫn sử dụng](https://thanhphuc1230.github.io/source_laravel/#/content-management)

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

## 🔒 Bảo mật

- **Role-based Access Control**: Phân quyền theo level (Admin, Staff, Users)
- **Route-level Protection**: Middleware bảo vệ routes nhạy cảm
- **Request Authorization**: BaseAdminRequest kiểm tra quyền truy cập
- **Multi-layer Security**: Bảo mật nhiều lớp từ Route → Request → Controller

- **Authentication**: Laravel Sanctum
- **Input Validation**: Form requests
- **CSRF Protection**: Built-in Laravel protection
- **File Upload**: Secure image handling

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
- **RateLimitService**: IP-based rate limiting cho login và contact

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

- **Documentation**: [Online Docs](https://thanhphuc1230.github.io/source_laravel)
- **Issues**: [GitHub Issues](https://github.com/Thanhphuc1230/source_laravel/issues)

