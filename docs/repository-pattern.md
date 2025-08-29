# Repository Pattern Implementation Guide

## Overview

This project uses the Repository Design Pattern to separate the data access logic from the business logic, making the codebase more maintainable and testable.

Repository Pattern giúp giảm thiểu code trùng lặp trong các controller, tập trung logic truy vấn dữ liệu vào một nơi, và cải thiện khả năng bảo trì của ứng dụng.

## Project Structure

```
app/
  Repositories/
    Interfaces/
      RepositoryInterface.php
      ProductRepositoryInterface.php
      NewsRepositoryInterface.php
      CateProductRepositoryInterface.php
      CateNewRepositoryInterface.php
      ...
    Eloquent/
      BaseRepository.php
      ProductRepository.php
      NewsRepository.php
      CateProductRepository.php
      CateNewRepository.php
      ...
  Providers/
    RepositoryServiceProvider.php
```

## How to Use

### Using Existing Repositories

1. Inject the repository in your controller's constructor:

```php
protected $productRepository;

public function __construct(ProductRepositoryInterface $productRepository)
{
    $this->productRepository = $productRepository;
    // other initialization code
}
```

2. Use the repository methods in your controller:

```php
// Get filtered and paginated data
$filters = [
    'search' => $request->input('search'),
    'category' => $request->input('category')
];
$products = $this->productRepository->getFilteredProducts($filters);

// Create new record
$data = $request->validated();
// UUID và timestamps được tự động xử lý trong repository
$product = $this->productRepository->create($data);

// Find by uuid
$product = $this->productRepository->findByUuid($uuid);

// Update record
$this->productRepository->update($data, $id);

// Delete record
$this->productRepository->delete($id);
```

### Creating a New Repository

1. Create an interface that extends the base `RepositoryInterface`:

```php
// app/Repositories/Interfaces/YourModelRepositoryInterface.php
namespace App\Repositories\Interfaces;

interface YourModelRepositoryInterface extends RepositoryInterface
{
    // Add specific methods for this repository
    public function getFilteredData(array $filters, $perPage = 10);
}
```

2. Create the repository implementation:

```php
// app/Repositories/Eloquent/YourModelRepository.php
namespace App\Repositories\Eloquent;

use App\Models\YourModel;
use App\Repositories\Interfaces\YourModelRepositoryInterface;

class YourModelRepository extends BaseRepository implements YourModelRepositoryInterface
{
    public function __construct(YourModel $model)
    {
        parent::__construct($model);
    }

    public function getFilteredData(array $filters, $perPage = 10)
    {
        $query = $this->model->query();
        
        // Apply filters
        if (isset($filters['search'])) {
            // Add search logic
        }
        
        return $query->paginate($perPage);
    }
}
```

3. Register the new repository in the `RepositoryServiceProvider`:

```php
// app/Providers/RepositoryServiceProvider.php
$this->app->bind(
    \App\Repositories\Interfaces\YourModelRepositoryInterface::class, 
    \App\Repositories\Eloquent\YourModelRepository::class
);
```

## Benefits

1. **Code Reusability**: Common database operations are defined in the base repository.
2. **Maintainability**: Changes to data access logic are isolated to repositories.
3. **Testability**: Controllers can be unit tested with mock repositories.
4. **Consistency**: Standardized data access methods across the application.
5. **Reduced Duplicate Code**: Eliminates repeated query patterns in controllers.

## Best Practices

1. Always type-hint the repository interface, not the concrete implementation.
2. Keep repositories focused on data access - business logic belongs in services.
3. Use model events for non-repository related tasks (e.g., clearing cache).
4. Consider adding a caching layer to repositories for frequently accessed data.

## Automatic Data Handling

BaseRepository đã được cải tiến để tự động xử lý một số trường dữ liệu phổ biến:

1. **UUID**: Tự động tạo UUID cho các bản ghi mới thông qua phương thức `handleUuid()`
2. **Timestamps**: Tự động xử lý `created_at` và `updated_at` thông qua phương thức `handleTimestamps()`
3. **Status**: Tự động đặt giá trị mặc định cho status khi tạo mới

```php
// Trong BaseRepository
protected function prepareDataForCreate(array $data)
{
    // Handle timestamps
    $data = $this->handleTimestamps($data);
    
    // Handle UUID
    $data = $this->handleUuid($data);
    
    // Handle default status if applicable
    if (!isset($data['status']) && in_array('status', $this->model->getFillable())) {
        $data['status'] = 1;
    }
    
    return $data;
}
```

Nhờ các cải tiến này, controller trở nên gọn nhẹ hơn và tập trung vào xử lý business logic thay vì các chi tiết tạo dữ liệu cơ bản.

## Implemented Controllers

Repository Pattern đã được triển khai thành công trong các controller sau:

1. **ProductController**: Sử dụng ProductRepositoryInterface
2. **NewsController**: Sử dụng NewsRepositoryInterface
3. **CateProductController**: Sử dụng CateProductRepositoryInterface
4. **CateNewController**: Sử dụng CateNewRepositoryInterface

### Ví dụ từ CateProductController

```php
class CateProductController extends BaseController
{
    protected $cateProductRepository;

    public function __construct(CateProductRepositoryInterface $cateProductRepository, $imageFolder = 'cate_product')
    {
        $this->cateProductRepository = $cateProductRepository;
        // ...khởi tạo khác
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'parent_id' => $request->has('category') ? $request->input('category') : null,
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];

        $data['list'] = $this->cateProductRepository->getFilteredCategories($filters);
        // ...xử lý khác
    }
}
```

Lợi ích của việc refactor này bao gồm:
- Giảm thiểu mã trùng lặp giữa các controller
- Đơn giản hóa logic truy vấn trong controller
- Tự động hóa xử lý UUID, timestamps trong repository
- Cải thiện khả năng bảo trì và test
