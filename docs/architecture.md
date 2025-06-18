# Architecture Overview

## 🏗️ Kiến trúc tổng quan

Laravel Admin System được xây dựng theo kiến trúc **trait-based** nhằm tối ưu hóa việc tái sử dụng code và maintainability.

## 📊 Sơ đồ kiến trúc

```
┌─────────────────────────────────────────────────────┐
│                   Frontend                          │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │
│  │   Admin UI  │  │  Public UI  │  │     API     │  │
│  └─────────────┘  └─────────────┘  └─────────────┘  │
└─────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────┐
│                 Controllers                         │
│  ┌─────────────────────────────────────────────────┐ │
│  │              BaseController                     │ │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐ │
│  │  │ImageHandler │  │DataRemoval  │  │SlugHandler  │ │
│  │  │    Trait    │  │   Trait     │  │   Trait     │ │
│  │  └─────────────┘  └─────────────┘  └─────────────┘ │
│  └─────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────┐
│                   Services                          │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │
│  │ImageService │  │DataRemoval  │  │StatusMgmt   │  │
│  │             │  │   Service   │  │   Service   │  │
│  └─────────────┘  └─────────────┘  └─────────────┘  │
└─────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────┐
│                    Models                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  │
│  │   Product   │  │    News     │  │    Page     │  │
│  │             │  │             │  │             │  │
│  └─────────────┘  └─────────────┘  └─────────────┘  │
└─────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────┐
│                  Database                           │
│              MySQL/PostgreSQL                      │
└─────────────────────────────────────────────────────┘
```

## 🧩 Trait System

### 1. **ImageHandlerTrait**
Xử lý tất cả logic liên quan đến image:

```php
trait ImageHandlerTrait
{
    protected function handleSingleImage(Request $request, $model = null, $folder = null, $field = 'image');
    protected function handleMultipleImages(Request $request, $model = null, $folder = null, $field = 'image_detail');
}
```

**Tính năng:**
- Upload và resize ảnh
- Convert sang WebP tự động
- Validation file type và size
- Xóa ảnh cũ khi update

### 2. **DataRemovalTrait**
Xử lý logic xóa data và cleanup:

```php
trait DataRemovalTrait
{
    public function destroyData(string $uuid);
    public function destroyAllData(Request $request);
}
```

**Tính năng:**
- Xóa single/multiple records
- Cleanup associated images
- Soft delete support
- Bulk operations

### 3. **SlugHandlerTrait**
Xử lý unique slug generation:

```php
trait SlugHandlerTrait
{
    protected function generateUniqueSlug(string $title, string $modelClass, ?string $currentUuid = null): string;
}
```

**Tính năng:**
- Tạo slug từ tiêu đề
- Đảm bảo unique với suffix numbering
- Support update operations
- SEO-friendly URLs

## 🔧 Services Layer

### 1. **ImageService**
Business logic cho image processing:

```php
class ImageService
{
    public function saveImage(Request $request, string $folder, string $field, array $config);
    public function saveMultipleImages(Request $request, string $folder, string $field, array $config);
    public function deleteImage(string $imagePath);
}
```

### 2. **DataRemovalService**
Business logic cho data removal:

```php
class DataRemovalService
{
    public function destroyData(string $modelClass, string $uuid, string $imageFolder);
    public function destroyAllByUUIDs(string $modelClass, array $uuids, string $imageFolder);
}
```

### 3. **StatusManagementService**
Business logic cho status management:

```php
class StatusManagementService
{
    public function updateStatus(string $uuid, int $status, string $name, string $modelClass);
    public function updateStt(Request $request, string $uuid, string $modelClass);
}
```

## 🎯 Design Patterns

### 1. **Trait Pattern**
- **Mục đích**: Code reuse across controllers
- **Lợi ích**: DRY principle, maintainability
- **Sử dụng**: ImageHandler, DataRemoval, SlugHandler

### 2. **Service Pattern**
- **Mục đích**: Business logic separation
- **Lợi ích**: Testability, single responsibility
- **Sử dụng**: ImageService, DataRemovalService

### 3. **Repository Pattern** (Optional)
- **Mục đích**: Data access abstraction
- **Lợi ích**: Database independence, testing
- **Trạng thái**: Có thể implement sau

## 📁 Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # Admin controllers
│   │   │   ├── BaseController.php
│   │   │   ├── ProductController.php
│   │   │   └── ...
│   │   └── Frontend/        # Frontend controllers
│   ├── Requests/            # Form requests
│   └── Middleware/          # Custom middleware
├── Models/                  # Eloquent models
├── Services/                # Business logic services
├── Traits/                  # Reusable traits
└── Helpers/                 # Helper functions
```

## 🔄 Request Flow

1. **Request** → Router → Controller
2. **Controller** → Validate (Form Request)
3. **Controller** → Use Traits (ImageHandler, SlugHandler)
4. **Controller** → Call Services (Business Logic)
5. **Services** → Interact with Models
6. **Models** → Database Operations
7. **Response** → View/JSON

## 🚀 Benefits

### ✅ **Advantages**
- **Code Reuse**: 70% reduction in duplicate code
- **Maintainability**: Centralized logic
- **Testability**: Service layer separation
- **Consistency**: Uniform behavior across modules
- **Scalability**: Easy to add new modules

### ⚠️ **Considerations**
- **Learning Curve**: New developers need to understand trait system
- **Debugging**: Multiple layers can complicate debugging
- **Over-engineering**: May be complex for simple requirements

## 🔮 Future Enhancements

1. **Repository Pattern**: For better data access abstraction
2. **Event System**: For decoupled notifications
3. **Cache Layer**: For improved performance
4. **API Versioning**: For API evolution
5. **Queue System**: For background processing 