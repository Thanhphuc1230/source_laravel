# Laravel Admin System

> Hệ thống quản trị Laravel hiện đại với architecture tối ưu và UI/UX thân thiện

## 🚀 Tính năng chính

- **Quản lý Content**: Sản phẩm, tin tức, trang nội dung, slider
- **Phân loại linh hoạt**: Danh mục sản phẩm, danh mục tin tức với cấu trúc cây
- **Xử lý Media**: Upload, resize, convert WebP tự động
- **SEO Friendly**: Slug tự động, meta tags, sitemap
- **Responsive Design**: Giao diện responsive trên mọi thiết bị
- **Analytics**: Thống kê truy cập và báo cáo

## 🏗️ Kiến trúc

### Backend
- **Laravel 10**: Framework PHP hiện đại
- **PHP 8.3+**: Sử dụng các tính năng mới nhất
- **MySQL**: Cơ sở dữ liệu quan hệ
- **Trait-based Architecture**: Code tái sử dụng cao

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
- [Contributing](contributing.md)

## 📊 Performance

- **Image Optimization**: WebP conversion, quality optimization
- **Caching**: Redis/File cache cho performance
- **Database**: Optimized queries, indexing
- **Assets**: Minification, compression

## 🔒 Bảo mật

- **Authentication**: Laravel Sanctum
- **Authorization**: Role-based permissions
- **Input Validation**: Form requests
- **CSRF Protection**: Built-in Laravel protection
- **File Upload**: Secure file handling

## 📝 Changelog

Xem [CHANGELOG.md](changelog.md) để biết các thay đổi mới nhất.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](license.md) file for details.

## 🤝 Contributing

Contributions are welcome! Please read [CONTRIBUTING.md](contributing.md) for details.

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/Thanhphuc1230/source_laravel/issues)
- **Email**: thanhphuc1230@example.com
- **Documentation**: [Online Docs](https://thanhphuc1230.github.io/source_laravel) 