# Quản lý Trang

## 📄 Tổng quan

Module quản lý trang cho phép bạn tạo và quản lý các trang nội dung tĩnh như "Giới thiệu", "Liên hệ", "Chính sách", "Điều khoản"... Hệ thống hỗ trợ cấu trúc phân cấp, đa ngôn ngữ và SEO optimization.

## 🚀 Tính năng chính

- **CRUD Operations**: Tạo, đọc, cập nhật, xóa trang
- **Cấu trúc phân cấp**: Hỗ trợ trang con và trang cha
- **Đa ngôn ngữ**: Hỗ trợ tiếng Việt và tiếng Anh
- **SEO Friendly**: Tự động tạo slug, meta tags, keywords
- **Footer Display**: Hiển thị links trong footer
- **Tìm kiếm**: Tìm kiếm theo tên trang
- **Bulk Operations**: Xóa nhiều trang cùng lúc
- **Status Management**: Publish/Draft trang
- **Cache System**: Tự động clear cache khi có thay đổi

## 📊 Danh sách trang

### Truy cập
**Admin Panel → Trang → Danh sách**

### Các cột hiển thị
- **Tên trang**: Tiêu đề trang và slug
- **Trạng thái**: Published/Draft
- **Thứ tự**: Số thứ tự sắp xếp
- **Ngày cập nhật**: Lần cập nhật cuối
- **Actions**: Edit, Delete, Status toggle

### Tính năng tìm kiếm
```php
// Tìm kiếm theo:
- Tên trang (name_vn)
- Trạng thái (active/inactive)
```

### Bulk Actions
- ✅ **Chọn nhiều**: Checkbox để chọn nhiều trang
- ✅ **Xóa hàng loạt**: Xóa nhiều trang cùng lúc
- ✅ **Cleanup tự động**: Tự động xóa ảnh liên quan

## ➕ Thêm trang mới

### Form fields

#### **Thông tin cơ bản**
```
- Tên trang (name_vn) *
- Tên tiếng Anh (name_en)
- Slug (tự động hoặc custom)
- Nội dung tiếng Việt (content_vn) *
- Nội dung tiếng Anh (content_en)
```

#### **Cấu trúc & Hiển thị**
```
- Trang cha (parent_id)
- Hiển thị footer (footer)
- Thứ tự sắp xếp (stt)
- Trạng thái (status)
```

#### **SEO & Meta**
```
- Keywords (từ khóa)
- Description (mô tả)
- Meta Tags
- Open Graph
```

#### **Hình ảnh**
```
- Ảnh đại diện (image)
  - Tự động resize và optimize
  - Convert sang WebP
  - Alt text tự động
```

### Validation Rules
```php
'name_vn' => 'required|string|max:255',
'content_vn' => 'required',
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
'slug' => 'nullable|string|unique:tp_pages,slug',
'keywords' => 'nullable|string|max:500',
'description' => 'nullable|string|max:500',
'parent_id' => 'nullable|exists:tp_pages,id_page',
```

### Image Processing
- **Tự động resize**: Theo config định sẵn
- **WebP conversion**: Tối ưu dung lượng
- **Quality optimization**: 80% quality default
- **SEO optimization**: Alt text và title tags

## ✏️ Chỉnh sửa trang

### Truy cập
**Danh sách trang → Click "Sửa" → Form chỉnh sửa**

### Tính năng
- ✅ **Pre-fill data**: Tự động điền dữ liệu hiện có
- ✅ **Image preview**: Xem trước ảnh hiện tại
- ✅ **Image replacement**: Thay thế ảnh mới
- ✅ **Rich text editor**: CKEditor cho content
- ✅ **Slug validation**: Kiểm tra unique slug
- ✅ **Live preview**: Xem trước trang

### Content Editor
```javascript
// CKEditor configuration
- Rich text formatting
- Image insertion
- Link management
- Table support
- Source code editing
- Responsive preview
- Custom CSS classes
```

## 🏗️ Cấu trúc phân cấp

### Parent-Child Relationship
```php
// Example structure
Giới thiệu (parent_id: 0)
├── Lịch sử công ty (parent_id: 1)
├── Tầm nhìn sứ mệnh (parent_id: 1)
└── Đội ngũ (parent_id: 1)

Dịch vụ (parent_id: 0)
├── Tư vấn (parent_id: 5)
├── Thiết kế (parent_id: 5)
└── Triển khai (parent_id: 5)
```

### URL Structure
```
// Parent page
/page/gioi-thieu.html

// Child page
/page/gioi-thieu/lich-su-cong-ty.html

// Deep nesting support
/page/parent/child/grandchild.html
```

### Navigation Generation
```php
// Auto-generate navigation menu
- Parent pages in main menu
- Child pages in dropdown/submenu
- Breadcrumb navigation
- Sitemap generation
```

## 🔄 Quản lý trạng thái

### Toggle Status
```php
// Endpoint
PUT /admin/page/status/{uuid}/{status}/{field}

// Usage
- Publish/Draft trang
- Hiển thị/ẩn khỏi footer
- Enable/disable navigation
- Instant update với AJAX
```

### Sắp xếp thứ tự
```php
// Endpoint  
PUT /admin/page/numerical-order/{uuid}

// Data
{
    "stt": 10
}

// Usage
- Thay đổi thứ tự trong menu
- Thứ tự trong footer
- Priority trong sitemap
```

## 🗑️ Xóa trang

### Xóa đơn lẻ
```php
// Endpoint
DELETE /admin/page/{uuid}

// Process
1. Kiểm tra trang con (nếu có)
2. Tìm trang theo UUID
3. Xóa ảnh đại diện
4. Xóa record khỏi database
5. Clear cache và navigation
6. Dispatch PageChanged event
```

### Xóa hàng loạt
```php
// Endpoint
POST /admin/page/destroy-all

// Data
{
    "uuids": ["uuid1", "uuid2", "uuid3"]
}

// Process
1. Validate UUIDs và dependencies
2. Bulk delete images
3. Bulk delete records
4. Clear related caches
5. Rebuild navigation
```

## 🎨 Giao diện Admin

### List View
```blade
resources/views/admin/modules/page/list.blade.php

Features:
- Responsive table
- Search form
- Hierarchical display
- Pagination
- Bulk actions
- Status toggles
- Quick edit links
```

### Detail View
```blade
resources/views/admin/modules/page/detail.blade.php

Features:
- Rich text editor (CKEditor)
- Multi-language tabs
- Image upload with preview
- Parent page selector
- Footer checkbox
- SEO fields
- Form validation
- Save options
```

## 🏗️ Database Schema

### Pages Table
```sql
CREATE TABLE tp_pages (
    id_page INT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    name_vn VARCHAR(255) NOT NULL,
    name_en VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    content_vn LONGTEXT NOT NULL,
    content_en LONGTEXT,
    image VARCHAR(255),
    parent_id INT DEFAULT 0,
    footer BOOLEAN DEFAULT 0,
    status BOOLEAN DEFAULT 1,
    stt INT DEFAULT 0,
    keywords VARCHAR(500),
    description VARCHAR(500),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_parent (parent_id),
    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_stt (stt),
    INDEX idx_footer (footer),
    FULLTEXT(name_vn, content_vn, keywords)
);
```

### Relationships
```php
// Self-referencing relationship
public function parent()
{
    return $this->belongsTo(Page::class, 'parent_id', 'id_page');
}

public function children()
{
    return $this->hasMany(Page::class, 'parent_id', 'id_page');
}
```

## 🔄 Events & Cache

### Events Dispatched
```php
// Tạo trang mới
PageChanged::dispatch($page, 'created', $slug);

// Cập nhật trang
PageChanged::dispatch($page, 'updated', $slug);

// Thay đổi status
PageChanged::dispatch($page, 'status_updated');

// Thay đổi thứ tự
PageChanged::dispatch($page, 'order_updated');

// Xóa trang
PageChanged::dispatch($page, 'deleted');
```

### Cache Clearing
```php
// Auto clear related caches
- Page content cache
- Navigation menu cache
- Footer links cache
- Breadcrumb cache
- Sitemap cache
```

## 🎯 Use Cases

### Common Page Types

#### **About Pages**
```
- Giới thiệu công ty
- Lịch sử phát triển  
- Tầm nhìn sứ mệnh
- Đội ngũ lãnh đạo
- Chứng nhận
```

#### **Legal Pages**
```
- Chính sách bảo mật
- Điều khoản sử dụng
- Chính sách đổi trả
- Hướng dẫn thanh toán
- Chính sách vận chuyển
```

#### **Support Pages**
```
- Hướng dẫn sử dụng
- Câu hỏi thường gặp
- Liên hệ hỗ trợ
- Hướng dẫn đặt hàng
- Bảo hành
```

### Footer Integration
```php
// Auto-generate footer links
$footerPages = Page::where('footer', 1)
                  ->where('status', 1)
                  ->orderBy('stt', 'asc')
                  ->get();
```

## 🎯 SEO Features

### Auto SEO
```php
// Auto-generated from content
- Meta title: từ name_vn
- Meta description: từ content_vn (excerpt)
- Keywords: từ content analysis
- Slug: từ name_vn (auto transliterate)
```

### Manual SEO
```php
// Custom fields
- keywords: Custom keywords
- description: Custom meta description
- Open Graph tags
- Schema markup
```

### URL Structure
```
// Static page
/page/{slug}.html

// Hierarchical page
/page/{parent_slug}/{child_slug}.html

// Multi-level nesting
/page/{level1}/{level2}/{level3}.html
```

## 🔧 Configuration

### Page Settings
```php
// config/pages.php (custom)
'max_depth' => 3,  // Maximum nesting level
'auto_footer' => true,  // Auto include in footer
'cache_ttl' => 7200,  // Cache time (2 hours)
'excerpt_length' => 200,
'image_sizes' => [
    'thumb' => [150, 150],
    'banner' => [1200, 400]
],
```

### Navigation Configuration
```php
// Auto-generate navigation
'navigation' => [
    'include_parent' => true,
    'include_children' => true,
    'max_depth' => 2,
    'sort_by' => 'stt',
    'cache_key' => 'page_navigation'
]
```

## 📱 Frontend Display

### Page Templates
```blade
// Single page template
resources/views/frontend/page/show.blade.php

// Page with children
resources/views/frontend/page/with-children.blade.php

// Landing page
resources/views/frontend/page/landing.blade.php
```

### Responsive Design
- ✅ **Mobile-first**: Responsive layout
- ✅ **Fast loading**: Optimized content
- ✅ **Accessibility**: WCAG compliant
- ✅ **Print-friendly**: CSS print styles

## 🎯 Best Practices

### Content Strategy
1. **Clear hierarchy**: Logical page structure
2. **Consistent naming**: Uniform naming convention
3. **Quality content**: Well-written, useful content
4. **Regular updates**: Keep content fresh
5. **User-focused**: Write for user needs

### SEO Optimization
1. **Unique content**: Avoid duplicate content
2. **Proper headings**: Use H1, H2, H3 structure
3. **Internal linking**: Link related pages
4. **Meta optimization**: Optimize meta tags
5. **Schema markup**: Implement structured data

### Performance Tips
1. **Content optimization**: Minimize HTML
2. **Image optimization**: Use WebP format
3. **Cache strategy**: Implement proper caching
4. **CDN usage**: Serve static content from CDN
5. **Lazy loading**: Load content on demand
