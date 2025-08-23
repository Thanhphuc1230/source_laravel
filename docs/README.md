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

## 📁 Cấu trúc dự án

```
source_laravel_10/
├── app/
│   ├── Http/Controllers/Admin/    # Admin controllers
│   ├── Models/                    # Eloquent models
│   ├── Traits/                    # Reusable traits
│   ├── Services/                  # Business logic
│   └── Helpers/                   # Helper functions
├── resources/views/admin/         # Admin views
├── public/admin/                  # Admin assets
└── docs/                         # Documentation
```

## 🔧 Cài đặt

```bash
# Clone repository
git clone <repository-url>
cd source_laravel_10

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

## 📖 Hướng dẫn

- [Cài đặt chi tiết](installation.md)
- [Cấu hình hệ thống](configuration.md)
- [Quản lý Content](content-management.md)
- [Tùy chỉnh giao diện](customization.md)
- [API Documentation](api.md)
- [Deployment](deployment.md)

## 🛠️ Phát triển

- [Architecture Overview](architecture.md)
- [Coding Standards](coding-standards.md)
- [Traits System](traits.md)
- [Database Schema](database.md)
- [Testing Guide](testing.md)
- [Contributing](contributing.md)

## 📊 Performance

- **Image Optimization**: WebP conversion, quality optimization
- **Caching**: Redis (Predis) cho Cache/Session/Queue
- **Database**: Optimized queries với eager loading, select optimization
- **Assets**: Minification, compression
- **Recent Optimizations**:
  - ✅ Query optimization với select fields
  - ✅ Cache implementation cho frontend
  - ✅ Role-based authorization system
  - ✅ Product settings management
  - ✅ File manager ordering optimization
  - ✅ Bulk operations với cleanup tự động

## 🔒 Bảo mật

- **Authentication**: Laravel Sanctum
- **Authorization**: Role-based permissions
- **Input Validation**: Form requests
- **CSRF Protection**: Built-in Laravel protection
- **File Upload**: Secure file handling

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

This project is licensed under the MIT License - see the [LICENSE](license.md) file for details.

## 🤝 Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](contributing.md) for details.

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/Thanhphuc1230/source_laravel/issues)
- **Email**: thanhphuc1230@example.com
- **Documentation**: [Online Docs](https://thanhphuc1230.github.io/source_laravel) 