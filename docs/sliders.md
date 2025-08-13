# Quản lý Slider

## 🖼️ Tổng quan

Module quản lý slider cho phép bạn tạo và quản lý các hình ảnh banner, carousel hiển thị trên trang chủ website. Hệ thống hỗ trợ upload hình ảnh, thêm link và sắp xếp thứ tự hiển thị.

## 🚀 Tính năng chính

- **CRUD Operations**: Tạo, đọc, cập nhật, xóa slider
- **Image Upload**: Upload và optimize hình ảnh
- **Link Management**: Thêm link redirect cho mỗi slide
- **Order Management**: Sắp xếp thứ tự hiển thị
- **Status Control**: Kích hoạt/tắt slider
- **Bulk Operations**: Xóa nhiều slider cùng lúc
- **Cache System**: Tự động clear cache khi có thay đổi
- **Responsive**: Tối ưu cho mọi thiết bị

## 📊 Danh sách slider

### Truy cập
**Admin Panel → Slider → Danh sách**

### Các cột hiển thị
- **Hình ảnh**: Preview thumbnail
- **Tên**: Tiêu đề slider
- **Link**: URL redirect
- **Trạng thái**: Active/Inactive
- **Thứ tự**: Số thứ tự hiển thị
- **Ngày tạo**: Thời gian tạo
- **Actions**: Edit, Delete, Status toggle

### Tính năng tìm kiếm
```php
// Tìm kiếm theo:
- Tên slider (name_vn)
- Trạng thái (active/inactive)
```

### Bulk Actions
- ✅ **Chọn nhiều**: Checkbox để chọn nhiều slider
- ✅ **Xóa hàng loạt**: Xóa nhiều slider cùng lúc
- ✅ **Cleanup tự động**: Tự động xóa ảnh liên quan

## ➕ Thêm slider mới

### Form fields

#### **Thông tin cơ bản**
```
- Tên slider (name_vn) *
- Link (URL redirect khi click)
- Hình ảnh (image) *
- Thứ tự hiển thị (stt)
- Trạng thái (status)
```

### Validation Rules
```php
'name_vn' => 'required|string|max:255',
'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
'link' => 'nullable|url|max:500',
'stt' => 'nullable|integer|min:0',
'status' => 'boolean',
```

### Image Requirements
```
- **Kích thước khuyến nghị**: 1920x800px (ratio 2.4:1)
- **Format**: JPEG, PNG, WebP
- **Dung lượng tối đa**: 5MB
- **Chất lượng**: High resolution cho responsive
```

### Image Processing
- **Tự động resize**: Theo config định sẵn
- **WebP conversion**: Tối ưu dung lượng
- **Quality optimization**: 85% quality cho slider
- **Responsive versions**: Multiple sizes tự động

## ✏️ Chỉnh sửa slider

### Truy cập
**Danh sách slider → Click "Sửa" → Form chỉnh sửa**

### Tính năng
- ✅ **Pre-fill data**: Tự động điền dữ liệu hiện có
- ✅ **Image preview**: Xem trước ảnh hiện tại
- ✅ **Image replacement**: Thay thế ảnh mới
- ✅ **Link validation**: Kiểm tra URL hợp lệ
- ✅ **Live preview**: Xem trước slider

### Link Management
```php
// Link types supported
- External links: https://example.com
- Internal pages: /page/about.html
- Product links: /product/san-pham-123.html
- Category links: /category/danh-muc-abc.html
- No link: để trống để không redirect
```

## 🔄 Quản lý trạng thái

### Toggle Status
```php
// Endpoint
PUT /admin/slider/status/{uuid}/{status}/{field}

// Usage
- Kích hoạt/tắt slider
- Hiển thị/ẩn khỏi homepage
- Instant update với AJAX
```

### Sắp xếp thứ tự
```php
// Endpoint  
PUT /admin/slider/numerical-order/{uuid}

// Data
{
    "stt": 10
}

// Usage
- Thay đổi thứ tự hiển thị
- Drag & drop support (nếu có)
- Bulk reorder
```

### Order Logic
```php
// Hiển thị theo thứ tự
ORDER BY stt ASC, created_at DESC

// Số thứ tự nhỏ sẽ hiển thị trước
stt: 1 → Slide đầu tiên
stt: 2 → Slide thứ hai
stt: 3 → Slide thứ ba
```

## 🗑️ Xóa slider

### Xóa đơn lẻ
```php
// Endpoint
DELETE /admin/slider/{uuid}

// Process
1. Tìm slider theo UUID
2. Xóa file ảnh khỏi storage
3. Xóa record khỏi database
4. Clear slider cache
5. Dispatch SliderChanged event
```

### Xóa hàng loạt
```php
// Endpoint
POST /admin/slider/destroy-all

// Data
{
    "uuids": ["uuid1", "uuid2", "uuid3"]
}

// Process
1. Validate UUIDs
2. Bulk delete images từ storage
3. Bulk delete records
4. Clear related caches
5. Show success notification
```

## 🎨 Giao diện Admin

### List View
```blade
resources/views/admin/modules/slider/list.blade.php

Features:
- Responsive table
- Image thumbnails
- Search form
- Pagination
- Bulk actions
- Status toggles
- Quick edit links
- Order controls
```

### Detail View
```blade
resources/views/admin/modules/slider/detail.blade.php

Features:
- Image upload with preview
- Link input với validation
- Order number input
- Status checkbox
- Form validation
- Save options (Save & Back / Save & List)
```

## 🏗️ Database Schema

### Sliders Table
```sql
CREATE TABLE tp_sliders (
    id_slider INT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    name_vn VARCHAR(255) NOT NULL,
    link VARCHAR(500),
    image VARCHAR(255) NOT NULL,
    status BOOLEAN DEFAULT 1,
    stt INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_stt (stt),
    INDEX idx_created (created_at)
);
```

### Field Descriptions
```php
- id_slider: Primary key
- uuid: Unique identifier for URLs
- name_vn: Tên slider (để quản lý)
- link: URL redirect khi click vào slider
- image: Đường dẫn file ảnh
- status: 1=Active, 0=Inactive
- stt: Thứ tự hiển thị (số nhỏ hiển thị trước)
- created_at: Thời gian tạo
- updated_at: Thời gian cập nhật cuối
```

## 🔄 Events & Cache

### Events Dispatched
```php
// Tạo slider mới
SliderChanged::dispatch($slider, 'created');

// Cập nhật slider
SliderChanged::dispatch($slider, 'updated');

// Thay đổi status
SliderChanged::dispatch($slider, 'status_updated');

// Thay đổi thứ tự
SliderChanged::dispatch($slider, 'order_updated');

// Xóa slider
SliderChanged::dispatch($slider, 'deleted');
```

### Cache Implementation
```php
// Frontend cache
$data['sliders'] = Cache::remember('slider_cache', 
    config('cache.ttl.slider_cache', 3600), function() {
    return Slider::where('status', 1)
                 ->orderBy('stt', 'asc')
                 ->get();
});
```

### Cache Clearing
```php
// Auto clear related caches when slider changes
- Homepage slider cache
- Mobile slider cache
- Slider navigation cache
```

## 📱 Frontend Display

### Slider Implementation
```blade
<!-- Homepage slider -->
@if(isset($sliders) && $sliders->count() > 0)
<div class="main-slider">
    <div class="slider-container">
        @foreach($sliders as $slider)
        <div class="slide">
            @if($slider->link)
            <a href="{{ $slider->link }}" title="{{ $slider->name_vn }}">
            @endif
                <img src="{{ asset('images/slider/' . $slider->image) }}" 
                     alt="{{ $slider->name_vn }}"
                     loading="lazy">
            @if($slider->link)
            </a>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
```

### Responsive Design
```css
/* Mobile-first approach */
.main-slider {
    width: 100%;
    height: 300px; /* Mobile height */
}

@media (min-width: 768px) {
    .main-slider {
        height: 500px; /* Tablet height */
    }
}

@media (min-width: 1200px) {
    .main-slider {
        height: 600px; /* Desktop height */
    }
}
```

### JavaScript Integration
```javascript
// Swiper.js example
const slider = new Swiper('.main-slider', {
    autoplay: {
        delay: 5000,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    loop: true,
    effect: 'fade',
});
```

## 🎯 Design Guidelines

### Image Best Practices
1. **Consistent ratio**: Tất cả slider cùng tỉ lệ
2. **High quality**: Sử dụng ảnh chất lượng cao
3. **Text overlay**: Tránh text quan trọng ở vùng crop
4. **File size**: Tối ưu dung lượng cho tốc độ tải
5. **Alt text**: Luôn có alt text cho accessibility

### Content Strategy
1. **Clear message**: Thông điệp rõ ràng
2. **Call to action**: Có CTA nếu cần
3. **Brand consistency**: Nhất quán với brand
4. **Mobile friendly**: Kiểm tra trên mobile
5. **A/B testing**: Test hiệu quả các slider

### Performance Tips
1. **Lazy loading**: Load ảnh khi cần
2. **WebP format**: Sử dụng WebP cho browser hỗ trợ
3. **CDN delivery**: Serve ảnh từ CDN
4. **Preload**: Preload ảnh đầu tiên
5. **Optimize animations**: Smooth transitions

## 🔧 Configuration

### Slider Settings
```php
// config/slider.php (custom)
'max_slides' => 10,
'image_quality' => 85,
'cache_ttl' => 3600,
'dimensions' => [
    'desktop' => [1920, 800],
    'tablet' => [1024, 426],
    'mobile' => [768, 320]
],
'formats' => ['jpeg', 'jpg', 'png', 'webp'],
'max_size' => 5 * 1024 * 1024, // 5MB
```

### Frontend Integration
```php
// Service Provider hoặc View Composer
View::composer('frontend.layouts.master', function ($view) {
    $sliders = Cache::remember('active_sliders', 3600, function () {
        return Slider::where('status', 1)
                    ->orderBy('stt', 'asc')
                    ->get();
    });
    
    $view->with('sliders', $sliders);
});
```

## 🎯 Use Cases

### Common Slider Types

#### **Promotional Banners**
```
- Khuyến mãi sản phẩm
- Sale events
- New product launches
- Seasonal campaigns
```

#### **Brand Awareness**
```
- Company introduction
- Values và mission
- Testimonials
- Awards và certifications
```

#### **Navigation Aids**
```
- Category highlights
- Popular products
- Featured content
- Call-to-action buttons
```

### Link Strategies
```php
// Internal navigation
'/category/electronics.html'  // Category page
'/product/iphone-15-pro.html' // Product detail
'/page/about-us.html'         // Static page
'/news/company-news.html'     // News section

// External promotion
'https://partner-site.com'    // Affiliate links
'https://event-page.com'      // External events
'tel:+84123456789'           // Contact phone
'mailto:info@company.com'    // Email contact
```

## 🎯 Analytics Integration

### Tracking Setup
```javascript
// Google Analytics tracking
$('.slide a').on('click', function() {
    const sliderName = $(this).find('img').attr('alt');
    const sliderLink = $(this).attr('href');
    
    gtag('event', 'slider_click', {
        'slider_name': sliderName,
        'slider_link': sliderLink,
        'value': 1
    });
});
```

### Performance Metrics
- **Click-through rate**: Tỷ lệ click trên slider
- **Bounce rate**: User behavior sau khi click
- **Conversion rate**: Chuyển đổi từ slider
- **Load time**: Thời gian tải slider
- **Mobile performance**: Hiệu suất trên mobile
