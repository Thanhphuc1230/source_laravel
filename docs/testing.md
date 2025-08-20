# Testing Guide

> Hướng dẫn toàn diện về testing trong Laravel Admin System

## 🧪 Testing Overview

Dự án sử dụng **PHPUnit** làm testing framework chính, kết hợp với Laravel's testing features để đảm bảo code quality và reliability.

## 🚀 Quick Start

### Chạy tất cả tests
```bash
php artisan test
```

### Chạy specific test file
```bash
php artisan test tests/Unit/Admin/CateNewControllerTest.php
```

### Chạy tests với coverage report
```bash
php artisan test --coverage
```

## 📁 Test Structure

```
tests/
├── Feature/           # Feature tests (end-to-end)
├── Unit/             # Unit tests (individual components)
│   ├── Admin/        # Admin controller tests
│   └── Services/     # Service layer tests
└── TestCase.php      # Base test class
```

## 🔧 Test Environment

### Environment Configuration
- **`.env.testing`**: Dedicated testing environment
- **Database**: Separate test database
- **Cache**: Array driver for testing
- **Queue**: Sync driver for testing

### Test Database Setup
```bash
# Copy environment file
cp .env .env.testing

# Configure test database
DB_CONNECTION=mysql
DB_DATABASE=laravel_testing
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=

# Run migrations for testing
php artisan migrate --env=testing
```

## 🧩 Unit Tests

### Controller Testing

#### CateNewController Test
```php
<?php

namespace Tests\Unit\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\CateNewController;
use App\Models\CateNew;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CateNewControllerTest extends TestCase
{
    use RefreshDatabase;

    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new CateNewController();
    }

    public function test_index_method_returns_view()
    {
        $response = $this->controller->index();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('category', $response);
    }

    // More test methods...
}
```

#### Test Coverage
- ✅ **index()**: View data loading
- ✅ **search()**: Search functionality
- ✅ **filter()**: Category filtering
- ✅ **create()**: Create form display
- ✅ **edit()**: Edit form with data
- ✅ **order()**: Order management
- ✅ **destroy()**: Single item deletion
- ✅ **destroyAll()**: Bulk deletion

### Service Testing

#### RateLimitService Test
```php
<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\RateLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RateLimitServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_for_login_factory_method()
    {
        $service = RateLimitService::forLogin();
        $this->assertEquals('login', $service->getPrefix());
        $this->assertEquals(5, $service->getMaxAttempts());
    }

    // More test methods...
}
```

## 🏭 Test Factories

### CateNew Factory
```php
<?php

namespace Database\Factories;

use App\Models\CateNew;
use Illuminate\Database\Eloquent\Factories\Factory;

class CateNewFactory extends Factory
{
    protected $model = CateNew::class;

    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'name_vn' => $this->faker->words(2, true),
            'name_en' => $this->faker->words(2, true),
            'slug' => $this->faker->slug(),
            'status' => 1,
            'stt' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function active()
    {
        return $this->state(['status' => 1]);
    }

    public function inactive()
    {
        return $this->state(['status' => 0]);
    }
}
```

## 🎯 Testing Best Practices

### 1. Use RefreshDatabase Trait
```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourTest extends TestCase
{
    use RefreshDatabase;
}
```

### 2. Test Database Isolation
- Mỗi test có database state riêng biệt
- Sử dụng `RefreshDatabase` để reset database
- Không để test này ảnh hưởng test khác

### 3. Meaningful Test Names
```php
// Good
public function test_user_cannot_access_admin_without_permission()

// Bad
public function test_access()
```

### 4. Arrange-Act-Assert Pattern
```php
public function test_user_can_create_category()
{
    // Arrange
    $user = User::factory()->create(['level' => 1]);
    $categoryData = ['name_vn' => 'Test Category'];

    // Act
    $response = $this->actingAs($user)
        ->post('/admin/cate-news', $categoryData);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('tp_cate_news', $categoryData);
}
```

## 🔍 Common Testing Scenarios

### Authentication Testing
```php
public function test_requires_authentication()
{
    $response = $this->get('/admin/cate-news');
    $response->assertRedirect('/login');
}
```

### Authorization Testing
```php
public function test_requires_admin_level()
{
    $user = User::factory()->create(['level' => 2]);
    
    $response = $this->actingAs($user)
        ->get('/admin/system');
    
    $response->assertStatus(403);
}
```

### Form Validation Testing
```php
public function test_validates_required_fields()
{
    $user = User::factory()->create(['level' => 1]);
    
    $response = $this->actingAs($user)
        ->post('/admin/cate-news', []);
    
    $response->assertSessionHasErrors(['name_vn']);
}
```

## 📊 Test Coverage Goals

### Current Coverage
- **Controllers**: 25% (CateNewController covered)
- **Services**: 50% (RateLimitService covered)
- **Models**: 0% (pending)
- **Overall**: ~15%

### Target Coverage
- **Controllers**: 80% (all admin controllers)
- **Services**: 90% (all business logic)
- **Models**: 60% (core models)
- **Overall**: 75%

## 🚨 Troubleshooting

### Common Issues

#### 1. Database Connection Errors
```bash
# Ensure test database exists
mysql -u root -p -e "CREATE DATABASE laravel_testing;"

# Check .env.testing configuration
DB_DATABASE=laravel_testing
```

#### 2. Migration Errors
```bash
# Reset test database
php artisan migrate:fresh --env=testing

# Or use RefreshDatabase trait
use Illuminate\Foundation\Testing\RefreshDatabase;
```

#### 3. Redis Module Warning
```
Warning: Module "redis" is already loaded
```
- **Impact**: Không ảnh hưởng test execution
- **Cause**: PhpRedis extension conflict
- **Solution**: Ignore warning (non-critical)

## 📚 Additional Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing Best Practices](https://laravel.com/docs/testing#testing-best-practices)

## 🤝 Contributing to Tests

### Adding New Tests
1. Tạo test file trong `tests/Unit/` hoặc `tests/Feature/`
2. Extend `Tests\TestCase`
3. Use `RefreshDatabase` trait
4. Follow naming conventions
5. Ensure test isolation

### Test Naming Convention
- **Unit Tests**: `{ClassName}Test.php`
- **Feature Tests**: `{FeatureName}Test.php`
- **Test Methods**: `test_{description}`

---

**Next Steps**: Continue expanding test coverage for other controllers and services!
