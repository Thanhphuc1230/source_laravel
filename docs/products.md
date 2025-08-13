# Quản lý Sản phẩm

## 📦 Tổng quan

Module quản lý sản phẩm cho phép bạn tạo, chỉnh sửa, và quản lý các sản phẩm trên website. Hệ thống hỗ trợ phân loại theo danh mục, upload hình ảnh, và SEO optimization.

## 🚀 Tính năng chính

- **CRUD Operations**: Tạo, đọc, cập nhật, xóa sản phẩm
- **Danh mục phân cấp**: Hỗ trợ danh mục con và danh mục cha
- **Quản lý hình ảnh**: Upload ảnh đại diện và gallery ảnh chi tiết
- **SEO Friendly**: Tự động tạo slug, meta tags
- **Tìm kiếm & lọc**: Tìm kiếm theo tên, lọc theo danh mục
- **Bulk Operations**: Xóa nhiều sản phẩm cùng lúc
- **Status Management**: Kích hoạt/tắt sản phẩm
- **Sắp xếp**: Thay đổi thứ tự hiển thị

## 📊 Danh sách sản phẩm

### Truy cập
**Admin Panel → Sản phẩm → Danh sách**

### Các cột hiển thị
- **Hình ảnh**: Thumbnail sản phẩm
- **Tên sản phẩm**: Tên và slug
- **Danh mục**: Thuộc danh mục nào
- **Trạng thái**: Active/Inactive
- **Hiển thị trang chủ**: Có/Không
- **Thứ tự**: Số thứ tự sắp xếp
- **Ngày cập nhật**: Lần cập nhật cuối

### Tính năng tìm kiếm
```php
// Tìm kiếm theo:
- Tên sản phẩm (name_vn)
- Trạng thái (active/inactive)
- Danh mục sản phẩm
```

### Bulk Actions
- ✅ **Chọn nhiều**: Checkbox để chọn nhiều sản phẩm
- ✅ **Xóa hàng loạt**: Xóa nhiều sản phẩm cùng lúc
- ✅ **Cleanup tự động**: Tự động xóa ảnh liên quan

## ➕ Thêm sản phẩm mới

### Form fields

#### **Thông tin cơ bản**
```
- Tên sản phẩm (name_vn) *
- Tên tiếng Anh (name_en)
- Slug (tự động hoặc custom)
- Danh mục sản phẩm *
- Mô tả ngắn (description)
- Nội dung chi tiết (content)
```

#### **SEO & Meta**
```
- Meta Title
- Meta Description  
- Meta Keywords
- Open Graph Tags
```

#### **Hình ảnh**
```
- Ảnh đại diện (image) *
- Gallery ảnh (image_detail)
  - Upload multiple files
  - Tự động resize và optimize
  - Convert sang WebP
```

#### **Cấu hình**
```
- Hiển thị trang chủ (home)
- Thứ tự sắp xếp (stt)
- Trạng thái (status)
```

### Validation Rules
```php
'name_vn' => 'required|string|max:255',
'category_id' => 'required|exists:tp_cate_products,id',
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
'image_detail.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
'slug' => 'nullable|string|unique:tp_products,slug',
```

### Image Processing
- **Tự động resize**: Theo config định sẵn
- **WebP conversion**: Tối ưu dung lượng
- **Quality optimization**: 80% quality default
- **Multiple upload**: Hỗ trợ gallery ảnh

## ✏️ Chỉnh sửa sản phẩm

### Truy cập
**Danh sách sản phẩm → Click "Sửa" → Form chỉnh sửa**

### Tính năng
- ✅ **Pre-fill data**: Tự động điền dữ liệu hiện có
- ✅ **Image preview**: Xem trước ảnh hiện tại
- ✅ **Image replacement**: Thay thế ảnh mới
- ✅ **Gallery management**: Xóa/thêm ảnh trong gallery
- ✅ **Slug validation**: Kiểm tra unique slug

### Image Gallery Management
```javascript
// Xóa ảnh trong gallery
DELETE /admin/product/{uuid}/image/{index}

// Response
{
    "message": "Hình ảnh đã được xóa thành công."
}
```

## 🔄 Quản lý trạng thái

### Toggle Status
```php
// Endpoint
PUT /admin/product/status/{uuid}/{status}/{field}

// Usage
- Kích hoạt/tắt sản phẩm
- Hiển thị/ẩn khỏi trang chủ
- Instant update với AJAX
```

### Sắp xếp thứ tự
```php
// Endpoint  
PUT /admin/product/numerical-order/{uuid}

// Data
{
    "stt": 10
}
```

## 🗑️ Xóa sản phẩm

### Xóa đơn lẻ
```php
// Endpoint
DELETE /admin/product/{uuid}

// Process
1. Tìm sản phẩm theo UUID
2. Xóa ảnh đại diện và gallery
3. Xóa record khỏi database
4. Clear cache liên quan
5. Dispatch ProductChanged event
```

### Xóa hàng loạt
```php
// Endpoint
POST /admin/product/destroy-all

// Data
{
    "uuids": ["uuid1", "uuid2", "uuid3"]
}

// Process
1. Validate UUIDs
2. Bulk delete images
3. Bulk delete records
4. Clear related caches
5. Show success notification
```

## 🎨 Giao diện Admin

### List View
```blade
resources/views/admin/modules/product/list.blade.php

Features:
- Responsive table
- Search form
- Category filter
- Pagination
- Bulk actions
- Status toggles
- Quick edit links
```

### Detail View
```blade
resources/views/admin/modules/product/detail.blade.php

Features:
- Rich text editor (CKEditor)
- Image upload with preview
- Category selector
- SEO fields
- Form validation
- Save options (Save & Back / Save & List)
```

## 🏗️ Database Schema

### Products Table
```sql
CREATE TABLE tp_products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    name_vn VARCHAR(255) NOT NULL,
    name_en VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    content LONGTEXT,
    image VARCHAR(255),
    image_detail JSON,
    category_id INT,
    parent_id INT DEFAULT 0,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    home BOOLEAN DEFAULT 0,
    status BOOLEAN DEFAULT 1,
    stt INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_stt (stt)
);
```

## 🔄 Events & Cache

### Events Dispatched
```php
// Tạo sản phẩm mới
ProductChanged::dispatch($product, 'created', $slug);

// Cập nhật sản phẩm
ProductChanged::dispatch($product, 'updated', $slug);

// Thay đổi status
ProductChanged::dispatch($product, 'status_updated');

// Thay đổi thứ tự
ProductChanged::dispatch($product, 'order_updated');

// Xóa sản phẩm
ProductChanged::dispatch($product, 'deleted');
```

### Cache Clearing
```php
// Auto clear related caches
- Product listings
- Category counts
- Homepage products
- Sitemap cache
```

## 🔧 Configuration

### Image Settings
```php
// ImageHandlerTrait config
'convertToWebp' => true,
'quality' => 80,
'mimeTypes' => [
    'image/jpeg', 'image/png', 
    'image/jpg', 'image/gif', 'image/webp'
]
```

### Upload Limits
```php
// php.ini settings
upload_max_filesize = 2M
post_max_size = 8M
max_file_uploads = 20
```

## 🎯 Best Practices

### SEO Optimization
1. **Tên sản phẩm**: Chứa từ khóa chính
2. **Slug**: Ngắn gọn, có từ khóa
3. **Meta description**: 150-160 ký tự
4. **Ảnh alt text**: Mô tả rõ ràng
5. **Structured data**: Schema markup

### Performance Tips
1. **Optimize images**: Sử dụng WebP format
2. **Lazy loading**: Cho gallery ảnh
3. **CDN**: Serve ảnh từ CDN
4. **Cache**: Enable Redis cache
5. **Database indexing**: Index các field quan trọng

### Content Guidelines
1. **Unique content**: Tránh duplicate
2. **Quality images**: High resolution
3. **Detailed description**: Đầy đủ thông tin
4. **Category structure**: Phân loại hợp lý
5. **Regular updates**: Cập nhật thường xuyên
