# Quản lý Tin tức

## 📰 Tổng quan

Module quản lý tin tức cho phép bạn tạo, chỉnh sửa, và quản lý các bài viết tin tức trên website. Hệ thống hỗ trợ phân loại theo danh mục, SEO optimization, và hiển thị đa ngôn ngữ.

## 🚀 Tính năng chính

- **CRUD Operations**: Tạo, đọc, cập nhật, xóa bài viết
- **Danh mục phân cấp**: Hỗ trợ danh mục tin tức con và cha
- **Đa ngôn ngữ**: Hỗ trợ tiếng Việt và tiếng Anh
- **SEO Friendly**: Tự động tạo slug, meta tags, keywords
- **Tìm kiếm & lọc**: Tìm kiếm theo tiêu đề, lọc theo danh mục
- **Bulk Operations**: Xóa nhiều bài viết cùng lúc
- **Status Management**: Publish/Draft bài viết
- **Cache System**: Tự động clear cache khi có thay đổi

## 📊 Danh sách tin tức

### Truy cập
**Admin Panel → Tin tức → Danh sách**

### Các cột hiển thị
- **Hình ảnh**: Thumbnail bài viết
- **Tiêu đề**: Tên bài viết và slug
- **Danh mục**: Thuộc danh mục tin tức nào
- **Trạng thái**: Published/Draft
- **Hiển thị trang chủ**: Có/Không
- **Thứ tự**: Số thứ tự sắp xếp
- **Ngày cập nhật**: Lần cập nhật cuối

### Tính năng tìm kiếm
```php
// Tìm kiếm theo:
- Tiêu đề bài viết (name_vn)
- Trạng thái (active/inactive)
- Danh mục tin tức
```

### Bulk Actions
- ✅ **Chọn nhiều**: Checkbox để chọn nhiều bài viết
- ✅ **Xóa hàng loạt**: Xóa nhiều bài viết cùng lúc
- ✅ **Cleanup tự động**: Tự động xóa ảnh liên quan

## ➕ Thêm bài viết mới

### Form fields

#### **Thông tin cơ bản**
```
- Tiêu đề (name_vn) *
- Tiêu đề tiếng Anh (name_en)
- Slug (tự động hoặc custom)
- Danh mục tin tức *
- Giới thiệu tiếng Việt (intro_vn)
- Giới thiệu tiếng Anh (intro_en)
- Nội dung tiếng Việt (content_vn) *
- Nội dung tiếng Anh (content_en)
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
- Ảnh đại diện (image) *
  - Tự động resize và optimize
  - Convert sang WebP
  - Alt text tự động
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
'content_vn' => 'required',
'category_id' => 'required|exists:tp_cate_news,id',
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
'slug' => 'nullable|string|unique:tp_news,slug',
'keywords' => 'nullable|string|max:500',
'description' => 'nullable|string|max:500',
```

### Image Processing
- **Tự động resize**: Theo config định sẵn
- **WebP conversion**: Tối ưu dung lượng
- **Quality optimization**: 80% quality default
- **SEO optimization**: Alt text và title tags

## ✏️ Chỉnh sửa bài viết

### Truy cập
**Danh sách tin tức → Click "Sửa" → Form chỉnh sửa**

### Tính năng
- ✅ **Pre-fill data**: Tự động điền dữ liệu hiện có
- ✅ **Image preview**: Xem trước ảnh hiện tại
- ✅ **Image replacement**: Thay thế ảnh mới
- ✅ **Rich text editor**: CKEditor cho content
- ✅ **Slug validation**: Kiểm tra unique slug
- ✅ **Auto-save**: Tự động lưu draft

### Content Editor
```javascript
// CKEditor configuration
- Rich text formatting
- Image insertion
- Link management
- Table support
- Source code editing
- Responsive preview
```

## 🔄 Quản lý trạng thái

### Toggle Status
```php
// Endpoint
PUT /admin/news/status/{uuid}/{status}/{field}

// Usage
- Publish/Draft bài viết
- Hiển thị/ẩn khỏi trang chủ
- Featured/Normal status
- Instant update với AJAX
```

### Sắp xếp thứ tự
```php
// Endpoint  
PUT /admin/news/numerical-order/{uuid}

// Data
{
    "stt": 10
}

// Usage
- Thay đổi thứ tự hiển thị
- Drag & drop support
- Bulk reorder
```

## 🗑️ Xóa bài viết

### Xóa đơn lẻ
```php
// Endpoint
DELETE /admin/news/{uuid}

// Process
1. Tìm bài viết theo UUID
2. Xóa ảnh đại diện
3. Xóa record khỏi database
4. Clear cache liên quan
5. Dispatch NewsChanged event
```

### Xóa hàng loạt
```php
// Endpoint
POST /admin/news/destroy-all

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
resources/views/admin/modules/news/list.blade.php

Features:
- Responsive table
- Search form
- Category filter
- Pagination
- Bulk actions
- Status toggles
- Quick edit links
- Category name display
```

### Detail View
```blade
resources/views/admin/modules/news/detail.blade.php

Features:
- Rich text editor (CKEditor)
- Multi-language tabs
- Image upload with preview
- Category selector
- SEO fields
- Form validation
- Save options (Save & Back / Save & List)
```

## 🏗️ Database Schema

### News Table
```sql
CREATE TABLE tp_news (
    id_new INT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    name_vn VARCHAR(255) NOT NULL,
    name_en VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    intro_vn TEXT,
    intro_en TEXT,
    content_vn LONGTEXT NOT NULL,
    content_en LONGTEXT,
    image VARCHAR(255),
    category_id INT,
    keywords VARCHAR(500),
    description VARCHAR(500),
    home BOOLEAN DEFAULT 0,
    status BOOLEAN DEFAULT 1,
    stt INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_stt (stt),
    INDEX idx_home (home),
    FULLTEXT(name_vn, content_vn, keywords)
);
```

### Relationships
```php
// News belongs to Category
public function cate()
{
    return $this->belongsTo(CateNew::class, 'category_id', 'id_cate_new');
}
```

## 🔄 Events & Cache

### Events Dispatched
```php
// Tạo bài viết mới
NewsChanged::dispatch($news, 'created', $slug);

// Cập nhật bài viết
NewsChanged::dispatch($news, 'updated', $slug);

// Thay đổi status
NewsChanged::dispatch($news, 'status_updated');

// Thay đổi thứ tự
NewsChanged::dispatch($news, 'order_updated');

// Xóa bài viết
NewsChanged::dispatch($news, 'deleted');
```

### Cache Clearing
```php
// Auto clear related caches
- News listings
- Category counts
- Homepage news
- RSS feeds
- Sitemap cache
```

### Cache Implementation
```php
// Frontend cache example
$data['latest_news'] = Cache::remember('news_cache', 
    config('cache.ttl.news_cache', 3600), function() {
    return News::where('status', 1)
               ->orderBy('stt', 'asc')
               ->get();
});
```

## 🎯 SEO Features

### Auto SEO
```php
// Auto-generated from content
- Meta title: từ name_vn
- Meta description: từ intro_vn hoặc content_vn (excerpt)
- Keywords: từ content analysis
- Slug: từ name_vn (auto transliterate)
```

### Manual SEO
```php
// Custom fields
- keywords: Custom keywords
- description: Custom meta description
- Open Graph tags
- Twitter Card tags
```

### URL Structure
```
// News detail page
/news/{category_slug}/{news_slug}.html

// Category page  
/news/{category_slug}.html

// News archive
/news/
```

## 📱 Responsive Features

### Admin Panel
- ✅ **Mobile responsive**: Tables và forms
- ✅ **Touch-friendly**: Buttons và controls
- ✅ **Fast loading**: Optimized assets
- ✅ **Progressive enhancement**: Works without JS

### Frontend Display
- ✅ **Responsive images**: Automatic sizing
- ✅ **Lazy loading**: Performance optimization
- ✅ **Social sharing**: Built-in share buttons
- ✅ **Print-friendly**: CSS print styles

## 🔧 Configuration

### News Settings
```php
// config/news.php (custom)
'pagination' => 10,
'excerpt_length' => 200,
'image_sizes' => [
    'thumb' => [150, 150],
    'medium' => [300, 200],
    'large' => [800, 600]
],
'cache_ttl' => 3600,
```

### Editor Configuration
```javascript
// CKEditor config
{
    language: 'vi',
    toolbar: [
        'heading', 'bold', 'italic', 'link',
        'bulletedList', 'numberedList',
        'blockQuote', 'insertTable',
        'imageUpload', 'mediaEmbed'
    ],
    image: {
        toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
    }
}
```

## 🎯 Best Practices

### Content Creation
1. **Tiêu đề**: Rõ ràng, có từ khóa
2. **Giới thiệu**: Tóm tắt nội dung chính
3. **Nội dung**: Cấu trúc rõ ràng với headings
4. **Hình ảnh**: Chất lượng cao, có alt text
5. **Keywords**: Liên quan đến nội dung

### SEO Optimization
1. **Unique content**: Tránh duplicate
2. **Internal linking**: Liên kết bài viết liên quan
3. **Category structure**: Phân loại hợp lý
4. **Regular updates**: Cập nhật content thường xuyên
5. **Social sharing**: Encourage social engagement

### Performance Tips
1. **Image optimization**: WebP format
2. **Content chunking**: Pagination cho long articles
3. **Cache strategy**: Implement proper caching
4. **CDN usage**: Serve static assets from CDN
5. **Database indexing**: Optimize query performance
