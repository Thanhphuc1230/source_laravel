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

- **Authentication**: Laravel Sanctum
- **Input Validation**: Form requests
- **CSRF Protection**: Built-in Laravel protection
- **File Upload**: Secure image handling

## 📄 License

This project is licensed under the MIT License.

## 🤝 Contributing

Contributions are welcome! Please read the [contributing guide](https://thanhphuc1230.github.io/source_laravel/#/contributing) for details.

## 📞 Support

- **Documentation**: [Online Docs](https://thanhphuc1230.github.io/source_laravel)
- **Issues**: [GitHub Issues](https://github.com/Thanhphuc1230/source_laravel/issues)

