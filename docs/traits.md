# Traits System

## 🧩 Tổng quan

Traits System là backbone của Laravel Admin System, giúp tái sử dụng code và tạo ra kiến trúc modular, maintainable.

## 📋 Danh sách Traits

### 1. ImageHandlerTrait
### 2. DataRemovalTrait  
### 3. SlugHandlerTrait

---

## 🖼️ ImageHandlerTrait

### Mục đích
Centralized image processing logic cho tất cả admin controllers.

### Location
```
app/Traits/ImageHandlerTrait.php
```

### Methods

#### `handleSingleImage()`
```php
protected function handleSingleImage(
    Request $request, 
    $model = null, 
    $folder = null, 
    $field = 'image'
): ?string
```

**Parameters:**
- `$request`: HTTP request object
- `$model`: Current model instance (for updates)
- `$folder`: Image folder override
- `$field`: Form field name

**Returns:** Image filename hoặc null

**Usage:**
```php
// Store operation
$data['image'] = $this->handleSingleImage($request);

// Update operation  
$data['image'] = $this->handleSingleImage($request, $current);

// Custom field
$data['avatar'] = $this->handleSingleImage($request, $current, null, 'avatar');
```

#### `handleMultipleImages()`
```php
protected function handleMultipleImages(
    Request $request,
    $model = null, 
    $folder = null,
    $field = 'image_detail'
): ?string
```

**Parameters:** Tương tự `handleSingleImage()`

**Returns:** JSON string của array images

**Usage:**
```php
// Store multiple images
$data['image_detail'] = $this->handleMultipleImages($request);

// Update multiple images
$data['image_detail'] = $this->handleMultipleImages($request, $current);
```

### Configuration

Default config trong trait:
```php
protected $defaultImageConfig = [
    'convertToWebp' => true,
    'quality' => 80,
    'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']
];
```

### Features

- ✅ **Auto WebP conversion** - Tối ưu performance
- ✅ **Quality optimization** - Giảm file size
- ✅ **File validation** - Security & type checking
- ✅ **Old image cleanup** - Tự động xóa ảnh cũ
- ✅ **Multiple formats support** - JPEG, PNG, GIF
- ✅ **Folder organization** - Tự động tạo folder structure

---

## 🗑️ DataRemovalTrait

### Mục đích
Centralized data removal logic với image cleanup.

### Location
```
app/Traits/DataRemovalTrait.php
```

### Methods

#### `destroyData()`
```php
public function destroyData(string $uuid): JsonResponse
```

**Parameters:**
- `$uuid`: UUID của record cần xóa

**Returns:** JSON response

**Usage:**
```php
public function destroy(string $uuid)
{
    return $this->destroyData($uuid);
}
```

#### `destroyAllData()`
```php
public function destroyAllData(Request $request): JsonResponse
```

**Parameters:**
- `$request`: Request chứa array UUIDs

**Returns:** JSON response

**Usage:**
```php
public function destroyAll(Request $request)
{
    return $this->destroyAllData($request);
}
```

### Features

- ✅ **Bulk deletion** - Xóa nhiều records cùng lúc
- ✅ **Image cleanup** - Tự động xóa associated images
- ✅ **Error handling** - Graceful error responses
- ✅ **Transaction safety** - Database consistency
- ✅ **Audit trail** - Log deletion activities

### Image Fields Supported

Trait tự động detect và xóa các image fields:
```php
protected $imageFields = [
    'image', 'avatar', 'logo', 'favicon', 'image_detail'
];
```

---

## 🔗 SlugHandlerTrait

### Mục đích
Generate unique SEO-friendly slugs với auto-increment.

### Location
```
app/Traits/SlugHandlerTrait.php
```

### Methods

#### `generateUniqueSlug()`
```php
protected function generateUniqueSlug(
    string $title, 
    string $modelClass, 
    ?string $currentUuid = null
): string
```

**Parameters:**
- `$title`: Tiêu đề để tạo slug
- `$modelClass`: Model class để check uniqueness
- `$currentUuid`: UUID hiện tại (cho update operations)

**Returns:** Unique slug string

**Usage:**
```php
// Store operation
$data['slug'] = empty($data['slug']) ? 
    $this->generateUniqueSlug($data['name_vn'], $this->model::class) : 
    $data['slug'];

// Update operation
$data['slug'] = empty($data['slug']) ? 
    $this->generateUniqueSlug($data['name_vn'], $this->model::class, $uuid) : 
    $data['slug'];
```

### Algorithm

1. **Base slug creation** từ title
2. **Check existence** trong database
3. **Increment suffix** nếu trùng: `slug-1`, `slug-2`, etc.
4. **Exclude current record** trong update operations

### Examples

```php
// Input: "Sản phẩm mới"
// Output: "san-pham-moi"

// Nếu đã tồn tại:
// Output: "san-pham-moi-1"

// Nếu "san-pham-moi-1" cũng tồn tại:
// Output: "san-pham-moi-2"
```

### Features

- ✅ **Unicode support** - Vietnamese characters
- ✅ **Auto increment** - Unique suffix numbering
- ✅ **Update safe** - Exclude current record
- ✅ **SEO friendly** - Clean URL structure
- ✅ **Performance optimized** - Efficient database queries

---

## 🔧 Usage trong Controllers

### BaseController Integration

```php
class BaseController extends Controller
{
    use ImageHandlerTrait, DataRemovalTrait, SlugHandlerTrait;
    
    // Common properties
    protected $imageService;
    protected $dataRemovalService;
    protected $statusManagementService;
    protected $imageFolder;
}
```

### Typical Controller Pattern

```php
class ProductController extends BaseController
{
    public function store(ProductRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        
        // Use SlugHandlerTrait
        $data['slug'] = empty($data['slug']) ? 
            $this->generateUniqueSlug($data['name_vn'], $this->model::class) : 
            $data['slug'];
        
        // Use ImageHandlerTrait
        $data['image'] = $this->handleSingleImage($request);
        $data['image_detail'] = $this->handleMultipleImages($request);
        
        $this->model::create($data);
        toast('Thêm sản phẩm thành công', 'success');
        
        return $this->handleRedirect($request);
    }
    
    public function update(ProductRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        
        // Use SlugHandlerTrait với currentUuid
        $data['slug'] = empty($data['slug']) ? 
            $this->generateUniqueSlug($data['name_vn'], $this->model::class, $uuid) : 
            $data['slug'];
        
        // Use ImageHandlerTrait với current model
        $data['image'] = $this->handleSingleImage($request, $current);
        $data['image_detail'] = $this->handleMultipleImages($request, $current);
        
        $this->model::where('uuid', $uuid)->update($data);
        toast('Cập nhật sản phẩm thành công', 'success');
        
        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }
    
    // Use DataRemovalTrait
    public function destroy(string $uuid)
    {
        return $this->destroyData($uuid);
    }
    
    public function destroyAll(Request $request)
    {
        return $this->destroyAllData($request);
    }
}
```

## 🚀 Benefits

### Code Reuse
- **70% reduction** trong duplicate code
- **Consistent behavior** across all controllers
- **Single source of truth** cho common logic

### Maintainability
- **Centralized updates** - Sửa 1 chỗ, áp dụng toàn bộ
- **Easy testing** - Test traits riêng biệt
- **Clear separation** of concerns

### Performance
- **Optimized algorithms** - Efficient slug generation
- **Image optimization** - WebP conversion, quality control
- **Batch operations** - Bulk deletion support

## ⚠️ Considerations

### Learning Curve
- Developers cần hiểu trait system
- Debugging có thể phức tạp hơn

### Dependencies
- Traits depend on services (ImageService, DataRemovalService)
- Cần ensure proper dependency injection

### Testing
- Mock services khi test traits
- Test integration với controllers

## 🔮 Future Enhancements

1. **CRUDTrait** - Centralize CRUD operations
2. **SearchTrait** - Centralize search logic
3. **ValidationTrait** - Centralize validation patterns
4. **CacheTrait** - Centralize caching logic
5. **EventTrait** - Centralize event dispatching 