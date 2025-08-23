# Authorization System

> Hệ thống phân quyền và bảo mật cho Laravel Admin System

## 🔐 Tổng quan

Hệ thống authorization được thiết kế với **multi-layer security** để đảm bảo bảo mật tối đa:

```
User Request → Route Middleware → Request Authorization → Controller → Model
     ↓              ↓                    ↓                ↓         ↓
  Level Check   Route Protection    Request Check    Business   Database
                                                      Logic      Access
```

## 🎯 Phân quyền theo Level

### **Level 1 (Admin)**
- ✅ **Full Access**: Tất cả tính năng
- ✅ **Product Settings**: Quản lý cài đặt sản phẩm
- ✅ **System**: Quản lý cài đặt hệ thống
- ✅ **User Management**: Quản lý người dùng
- ✅ **Analytics**: Xem thống kê chi tiết

### **Level 2 (Staff)**
- ✅ **Content Management**: Sản phẩm, tin tức, trang
- ✅ **Order Management**: Quản lý đơn hàng
- ✅ **Customer Support**: Phản hồi, liên hệ
- ❌ **Product Settings**: Không truy cập được
- ❌ **System**: Không truy cập được

### **Level 3 (Users)**
- ❌ **Admin Area**: Không truy cập được
- ✅ **Frontend**: Chỉ truy cập frontend

## 🛡️ Security Layers

### **1. Route Level (CheckAdminLevel Middleware)**
```php
// Chỉ Admin mới truy cập được
->middleware('admin.level:1')

// Admin và Staff đều truy cập được
->middleware('admin.level:2')

// Không cần middleware (dùng BaseAdminRequest)
// Mặc định cho phép level >= 1
```

### **2. Request Level (BaseAdminRequest)**
```php
abstract class BaseAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return false;
        }

        // Check if user has admin level (level >= 1) and is active
        $user = auth()->user();
        
        return $user->level >= 1 && $user->status == 1;
    }
}
```

### **3. Controller Level**
```php
// Không cần authorize() calls
// Authorization đã được xử lý ở Request level
public function index()
{
    $data = $this->getBaseData();
    $data['settings'] = $this->model::orderBy('group')->paginate(20);
    return $this->view_admin('list', $data);
}
```

## 🔧 Implementation

### **Middleware Registration**
```php
// app/Http/Kernel.php
protected $middlewareAliases = [
    'admin.level' => \App\Http\Middleware\CheckAdminLevel::class,
];
```

### **Route Protection**
```php
// Product Settings - Chỉ Admin
Route::controller(ProductSettingController::class)
    ->prefix('product-setting')
    ->name('product-setting.')
    ->middleware('admin.level:1')
    ->group(function () {
        // ... routes
    });

// System - Chỉ Admin
Route::controller(SystemController::class)
    ->prefix('system')
    ->name('system.')
    ->middleware('admin.level:1')
    ->group(function () {
        // ... routes
    });
```

### **Request Authorization**
```php
// Tất cả Admin Request đều extend BaseAdminRequest
class ProductSettingRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        // Use BaseAdminRequest authorization (level >= 1)
        return parent::authorize();
    }
}
```

## 📁 File Structure

```
app/
├── Http/
│   ├── Middleware/
│   │   └── CheckAdminLevel.php          # Route protection
│   ├── Requests/Admin/
│   │   ├── BaseAdminRequest.php         # Base authorization
│   │   ├── ProductSettingRequest.php    # Product settings
│   │   └── SystemRequest.php            # System settings
│   └── Controllers/Admin/
│       ├── ProductSettingController.php # Product settings
│       └── SystemController.php         # System settings
└── Providers/
    └── AuthServiceProvider.php          # Policy registration
```

## 🚀 Lợi ích

### **1. Bảo mật cao**
- **Multi-layer protection** - Bảo mật nhiều lớp
- **Route-level security** - Chặn sớm ở route level
- **Request validation** - Kiểm tra quyền ở request level

### **2. Performance tốt**
- **Early blocking** - Chặn sớm, không load Controller
- **No database queries** - Không cần query để kiểm tra quyền
- **Fast redirect** - Redirect ngay nếu không có quyền

### **3. Dễ maintain**
- **Centralized logic** - Logic authorization tập trung
- **Consistent pattern** - Tất cả Request đều dùng cùng pattern
- **Easy to modify** - Dễ dàng thay đổi quyền

### **4. User Experience**
- **Clear messages** - Thông báo rõ ràng
- **Proper redirects** - Redirect về dashboard thay vì error
- **No crashes** - Không bị crash ứng dụng

## 🔍 Testing

### **Test Authorization**
```bash
# Test với user level 1 (Admin)
php artisan tinker
$user = User::find(1); $user->level = 1; $user->save();

# Test với user level 2 (Staff)  
$user = User::find(2); $user->level = 2; $user->save();

# Test với user level 3 (Users)
$user = User::find(3); $user->level = 3; $user->save();
```

### **Test Routes**
```bash
# Test Product Settings (Admin only)
curl -H "Authorization: Bearer {token}" /admin/product-setting

# Test System (Admin only)
curl -H "Authorization: Bearer {token}" /admin/system

# Test Products (Admin + Staff)
curl -H "Authorization: Bearer {token}" /admin/products
```

## 📝 Best Practices

### **1. Always use BaseAdminRequest**
```php
// ✅ Good
class MyRequest extends BaseAdminRequest

// ❌ Bad
class MyRequest extends FormRequest
```

### **2. Use middleware for sensitive routes**
```php
// ✅ Good - Protect sensitive routes
->middleware('admin.level:1')

// ❌ Bad - No protection
// No middleware
```

### **3. Keep authorization simple**
```php
// ✅ Good - Simple and clear
public function authorize(): bool
{
    return parent::authorize();
}

// ❌ Bad - Complex logic
public function authorize(): bool
{
    // Complex authorization logic
    if ($this->user()->hasRole('admin')) {
        if ($this->user()->can('manage_settings')) {
            return true;
        }
    }
    return false;
}
```

## 🔮 Future Enhancements

### **1. Permission-based System**
- Thay thế level-based bằng permission-based
- Granular permissions cho từng action
- Role-based permission assignment

### **2. API Token Management**
- Admin API token generation
- Token expiration và rotation
- Audit trail cho API access

### **3. Advanced Logging**
- Log mọi thay đổi authorization
- Failed access attempts logging
- Security event monitoring

---

**Lưu ý**: Hệ thống authorization này được thiết kế để **đơn giản, bảo mật và dễ maintain**. Không nên thêm logic phức tạp vào authorization system.
