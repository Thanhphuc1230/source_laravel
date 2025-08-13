# Quản lý Menu

## 🧭 Tổng quan

Module quản lý menu cho phép bạn tạo và quản lý hệ thống navigation của website. Hỗ trợ menu phân cấp, link đến pages/categories có sẵn, và tạo custom links. Hệ thống tự động đồng bộ với content thay đổi.

## 🚀 Tính năng chính

- **Menu phân cấp**: Hỗ trợ menu con nhiều cấp
- **Auto-sync**: Tự động đồng bộ với pages/categories
- **Multiple types**: Link đến page, category, hoặc custom URL
- **Bulk creation**: Tạo nhiều menu items cùng lúc
- **Order management**: Sắp xếp thứ tự hiển thị
- **Status control**: Kích hoạt/tắt menu items
- **Cache system**: Tự động clear cache khi có thay đổi
- **Responsive**: Tối ưu cho mobile navigation

## 📊 Quản lý menu

### Truy cập
**Admin Panel → Menu → Quản lý**

### Giao diện quản lý
- **Menu tree**: Hiển thị cấu trúc menu dạng cây
- **Add menu form**: Form thêm menu mới
- **Available items**: Danh sách pages/categories có thể thêm
- **Drag & drop**: Sắp xếp menu bằng kéo thả
- **Quick actions**: Edit, delete, toggle status

## 🔗 Loại menu

### 1. Page Menu
```php
// Link đến static pages
Type: 'page'
Object: Pages (About, Contact, Terms...)
URL: /page/{slug}.html
```

### 2. News Category Menu
```php
// Link đến danh mục tin tức
Type: 'cate_new'  
Object: News Categories
URL: /news/{category_slug}.html
```

### 3. Product Category Menu
```php
// Link đến danh mục sản phẩm
Type: 'cate_product'
Object: Product Categories  
URL: /category/{category_slug}.html
```

### 4. Custom Link Menu
```php
// Custom URL
Type: 'custom'
Link: Any URL (internal/external)
URL: {custom_link}
```

## ➕ Thêm menu mới

### Form fields

#### **Menu Information**
```
- Tên menu (name_vn) *
- Tên tiếng Anh (name_en)
- Loại menu (type) *
- Menu cha (parent_id)
- Thứ tự (stt)
- Link tùy chỉnh (link)
```

#### **Available Content**
```
- Pages: Danh sách các trang static
- News Categories: Danh mục tin tức (có children)
- Product Categories: Danh mục sản phẩm (có children)
```

### Menu Types

#### **Page Menu**
```php
// Select from available pages
$pages = Page::where('status', 1)
             ->orderBy('stt', 'asc')
             ->get();

// Auto-fill: name_vn, slug từ page
```

#### **Category Menu**
```php
// News categories với children
$newsCategories = CateNew::with('children')
                         ->where('status', 1)
                         ->where('parent_id', 0)
                         ->get();

// Product categories với children  
$productCategories = CateProduct::with('children')
                                ->where('status', 1)
                                ->where('parent_id', 0)
                                ->get();
```

#### **Custom Menu**
```php
// Manual input
name_vn: 'Trang chủ'
link: '/'
type: 'custom'
object_id: 0
```

### Validation Rules
```php
'type' => 'required|in:page,cate_new,cate_product,custom',
'name_vn' => 'required_if:type,custom|string|max:255',
'link' => 'nullable|string|max:500',
'parent_id' => 'nullable|exists:tp_menus,id_menu',
'object_ids' => 'array',
'object_ids.*' => 'integer',
```

## 🏗️ Cấu trúc menu

### Hierarchical Structure
```
Trang chủ (parent_id: 0)
Sản phẩm (parent_id: 0)
├── Điện thoại (parent_id: product_menu_id)
├── Laptop (parent_id: product_menu_id)
└── Phụ kiện (parent_id: product_menu_id)
Tin tức (parent_id: 0)
├── Công nghệ (parent_id: news_menu_id)
└── Khuyến mãi (parent_id: news_menu_id)
Giới thiệu (parent_id: 0)
Liên hệ (parent_id: 0)
```

### Database Relationships
```php
// Menu model
public function children()
{
    return $this->hasMany(Menu::class, 'parent_id', 'id_menu')
                ->where('status', 1)
                ->orderBy('stt', 'asc');
}

public function parent()
{
    return $this->belongsTo(Menu::class, 'parent_id', 'id_menu');
}
```

## 📝 Auto-generation Logic

### Name và Slug Auto-fill
```php
private function getNameVn(string $type, int $id): ?array
{
    switch ($type) {
        case 'page':
            $page = Page::find($id);
            return $page ? [
                'name_vn' => $page->name_vn, 
                'slug' => Str::slug($page->name_vn)
            ] : null;
            
        case 'cate_new':
            $cateNew = CateNew::find($id);
            return $cateNew ? [
                'name_vn' => $cateNew->name_vn,
                'slug' => Str::slug($cateNew->name_vn)
            ] : null;
            
        case 'cate_product':
            $cateProduct = CateProduct::find($id);
            return $cateProduct ? [
                'name_vn' => $cateProduct->name_vn,
                'slug' => Str::slug($cateProduct->name_vn)
            ] : null;
    }
}
```

### Bulk Menu Creation
```php
// Tạo nhiều menu từ object_ids array
foreach ($objectIds as $objectId) {
    $nameChild = $this->getNameVn($request->type, $objectId);
    
    $data = [
        'name_vn' => $nameChild['name_vn'] ?? $request->name_vn,
        'name_en' => $nameChild['name_en'] ?? $request->name_en,
        'slug' => $nameChild['slug'] ?? Str::slug($request->name_vn),
        'object_id' => $objectId,
        'parent_id' => $request->parent_id,
        'type' => $request->type,
        'uuid' => Str::uuid(),
    ];
    
    Menu::create($data);
}
```

## ✏️ Chỉnh sửa menu

### Edit Functionality
- ✅ **Inline editing**: Edit trực tiếp trên menu tree
- ✅ **Drag & drop**: Thay đổi vị trí và parent
- ✅ **Quick toggle**: Bật/tắt menu nhanh
- ✅ **Bulk actions**: Xóa nhiều menu cùng lúc

### Update Process
```php
public function update(Request $request, string $uuid)
{
    $data = array_merge(
        $request->except('_token'),
        ['updated_at' => now()]
    );

    $menu = Menu::where('uuid', $uuid)->first();
    $menu->update($data);
    
    // Dispatch event để clear cache
    MenuChanged::dispatch($menu, 'updated');
}
```

## 🔄 Quản lý trạng thái

### Toggle Status
```php
// Endpoint
PUT /admin/menu/status/{uuid}/{status}/{field}

// Auto cascade to children (optional)
- Tắt menu cha → Tắt tất cả menu con
- Bật menu con → Auto bật menu cha
```

### Order Management
```php
// Endpoint
PUT /admin/menu/numerical-order/{uuid}

// Data
{
    "stt": 10
}

// Ordering logic
ORDER BY stt ASC, created_at ASC
```

## 🗑️ Xóa menu

### Delete Single
```php
// Endpoint
DELETE /admin/menu/{uuid}

// Process
1. Kiểm tra menu con
2. Xóa hoặc reassign children
3. Clear navigation cache
4. Dispatch MenuChanged event
```

### Delete Multiple
```php
// Endpoint  
POST /admin/menu/destroy-all

// Handle children
- Delete children cùng parent
- Hoặc move children lên parent level
```

## 🎨 Giao diện Admin

### Menu Management Interface
```blade
resources/views/admin/modules/menu/detail.blade.php

Features:
- Menu tree với nested structure
- Available content panels
- Drag & drop ordering
- Inline editing
- Status toggles
- Add menu form
```

### Menu Tree Display
```javascript
// Menu tree với jQuery/sortable
$('.menu-tree').sortable({
    handle: '.menu-handle',
    placeholder: 'menu-placeholder',
    update: function(event, ui) {
        // Update order via AJAX
        updateMenuOrder();
    }
});
```

## 🏗️ Database Schema

### Menus Table
```sql
CREATE TABLE tp_menus (
    id_menu INT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    name_vn VARCHAR(255) NOT NULL,
    name_en VARCHAR(255),
    slug VARCHAR(255),
    type ENUM('page', 'cate_new', 'cate_product', 'custom'),
    object_id INT DEFAULT 0,
    parent_id INT DEFAULT 0,
    link VARCHAR(500),
    status BOOLEAN DEFAULT 1,
    stt INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_parent (parent_id),
    INDEX idx_status (status),
    INDEX idx_type_object (type, object_id),
    INDEX idx_stt (stt)
);
```

### Field Descriptions
```php
- type: Loại menu (page/cate_new/cate_product/custom)
- object_id: ID của object được link (page_id, category_id)
- parent_id: ID menu cha (0 = root level)
- link: Custom URL (cho type=custom)
- slug: URL slug (auto-generated hoặc custom)
```

## 🔄 Events & Cache

### Events Dispatched
```php
// Tạo menu mới
MenuChanged::dispatch($menu, 'created');

// Cập nhật menu
MenuChanged::dispatch($menu, 'updated');

// Thay đổi status
MenuChanged::dispatch($menu, 'status_updated');

// Thay đổi order
MenuChanged::dispatch($menu, 'order_updated');

// Xóa menu
MenuChanged::dispatch($menu, 'deleted');
```

### Cache Strategy
```php
// Main navigation cache
$menus = Cache::remember('main_navigation', 3600, function() {
    return Menu::with('children')
               ->where('status', 1)
               ->where('parent_id', 0)
               ->orderBy('stt', 'asc')
               ->get();
});

// Mobile navigation cache
$mobileMenus = Cache::remember('mobile_navigation', 3600, function() {
    return Menu::where('status', 1)
               ->whereIn('type', ['page', 'custom'])
               ->orderBy('stt', 'asc')
               ->get();
});
```

## 📱 Frontend Implementation

### Navigation Rendering
```blade
<!-- Main navigation -->
<nav class="main-navigation">
    <ul class="nav-menu">
        @foreach($menus as $menu)
        <li class="nav-item {{ $menu->children->count() > 0 ? 'has-children' : '' }}">
            <a href="{{ $menu->getUrl() }}" class="nav-link">
                {{ $menu->name_vn }}
            </a>
            
            @if($menu->children->count() > 0)
            <ul class="sub-menu">
                @foreach($menu->children as $child)
                <li class="sub-item">
                    <a href="{{ $child->getUrl() }}" class="sub-link">
                        {{ $child->name_vn }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach
    </ul>
</nav>
```

### URL Generation
```php
// Menu model method
public function getUrl()
{
    switch ($this->type) {
        case 'page':
            return route('page.show', $this->slug);
        case 'cate_new':
            return route('news.category', $this->slug);
        case 'cate_product':
            return route('product.category', $this->slug);
        case 'custom':
            return $this->link;
        default:
            return '#';
    }
}
```

### Responsive Menu
```css
/* Desktop menu */
.main-navigation {
    display: flex;
}

/* Mobile menu */
@media (max-width: 768px) {
    .main-navigation {
        display: none;
    }
    
    .mobile-menu {
        display: block;
    }
}
```

### JavaScript Enhancements
```javascript
// Mobile menu toggle
$('.mobile-menu-toggle').on('click', function() {
    $('.mobile-menu').toggleClass('active');
});

// Dropdown menus
$('.has-children > .nav-link').on('click', function(e) {
    if ($(window).width() <= 768) {
        e.preventDefault();
        $(this).next('.sub-menu').slideToggle();
    }
});
```

## 🎯 Best Practices

### Menu Structure
1. **Logical hierarchy**: Tổ chức menu hợp lý
2. **Limited depth**: Tối đa 3-4 cấp menu
3. **Clear naming**: Tên menu rõ ràng, dễ hiểu
4. **Consistent ordering**: Thứ tự logic và nhất quán
5. **Mobile-first**: Thiết kế ưu tiên mobile

### Performance Tips
1. **Cache menus**: Cache menu structure
2. **Lazy loading**: Load submenu khi cần
3. **Minimize queries**: Eager load relationships
4. **CDN assets**: Cache menu-related assets
5. **Debounce updates**: Debounce drag & drop updates

### SEO Considerations
1. **Semantic HTML**: Sử dụng nav, ul, li tags
2. **Breadcrumbs**: Implement breadcrumb navigation
3. **Schema markup**: Add navigation schema
4. **Internal linking**: Optimize internal link structure
5. **Mobile navigation**: Ensure mobile-friendly navigation

## 🔧 Configuration

### Menu Settings
```php
// config/menu.php (custom)
'max_depth' => 4,
'cache_ttl' => 3600,
'mobile_breakpoint' => 768,
'auto_sync' => true, // Auto sync với content changes
'cascade_status' => true, // Cascade status changes to children
```

### Frontend Integration
```php
// View Composer
View::composer(['frontend.layouts.header', 'frontend.layouts.mobile'], function ($view) {
    $menus = Cache::remember('site_navigation', 3600, function () {
        return Menu::getActiveMenusWithChildren();
    });
    
    $view->with('siteMenus', $menus);
});
```

## 📊 Analytics & Tracking

### Menu Analytics
```javascript
// Track menu clicks
$('.nav-link').on('click', function() {
    const menuText = $(this).text().trim();
    const menuUrl = $(this).attr('href');
    
    gtag('event', 'menu_click', {
        'menu_text': menuText,
        'menu_url': menuUrl,
        'menu_level': $(this).closest('ul').hasClass('sub-menu') ? 'sub' : 'main'
    });
});
```

### Performance Monitoring
- **Menu load time**: Thời gian render menu
- **Click tracking**: Theo dõi menu được click nhiều nhất
- **Mobile usage**: Usage patterns trên mobile
- **Error tracking**: Track broken menu links
- **A/B testing**: Test different menu structures
