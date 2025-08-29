<!-- docs/design-patterns.md -->

# Design Patterns trong Laravel Source

## Repository Pattern

Repository Pattern được triển khai để tách biệt logic truy vấn dữ liệu khỏi các controller, giúp code dễ bảo trì và kiểm thử hơn.

[Xem chi tiết về Repository Pattern](repository-pattern.md)

### Lợi ích chính

1. **Giảm thiểu code trùng lặp** trong controller
2. **Tập trung logic truy vấn dữ liệu** vào một nơi duy nhất
3. **Dễ dàng thay đổi logic truy vấn** mà không ảnh hưởng đến controller
4. **Hỗ trợ Unit Testing** tốt hơn với khả năng mock repository

### Cấu trúc triển khai

```
app/
  Repositories/
    Interfaces/        - Chứa các interface định nghĩa các phương thức
    Eloquent/          - Chứa các implementation của các interface
  Providers/
    RepositoryServiceProvider.php  - Đăng ký binding giữa interface và implementation
```

## Service Pattern

Service Pattern được sử dụng để chứa các business logic phức tạp, tách biệt khỏi controller.

### Các service hiện có

- `DataRemovalService`: Xử lý việc xóa dữ liệu và các tài nguyên liên quan
- `ImageService`: Xử lý việc tải lên và quản lý hình ảnh
- `ModelToggleService`: Xử lý việc bật/tắt và thay đổi thứ tự của model
- `RateLimitService`: Xử lý giới hạn tần suất truy cập API

## Trait Pattern

Traits được sử dụng để chia sẻ các chức năng giữa các lớp không liên quan.

### Các trait hiện có

- `DataRemovalTrait`: Cung cấp các phương thức để xóa dữ liệu
- `ImageHandlerTrait`: Cung cấp các phương thức để xử lý hình ảnh
- `SlugHandlerTrait`: Cung cấp các phương thức để tạo và xử lý slug

## Event-Listener Pattern

Event-Listener Pattern được sử dụng để xử lý các tác vụ không đồng bộ và tách biệt logic xử lý sự kiện.

### Các event-listener hiện có

- `CateNewChanged` / `ClearCateNewCache`: Xóa cache khi danh mục tin tức thay đổi
- `CateProductChanged` / `ClearCateProductCache`: Xóa cache khi danh mục sản phẩm thay đổi
- `NewsChanged` / `ClearNewsCache`: Xóa cache khi tin tức thay đổi
- `ProductChanged` / `ClearProductCache`: Xóa cache khi sản phẩm thay đổi

## Factory Pattern

Factory Pattern được sử dụng trong các database seeder và factory để tạo dữ liệu mẫu.

## Strategy Pattern

Strategy Pattern được áp dụng trong các xử lý phức tạp có nhiều cách triển khai khác nhau, như xử lý payment gateway.
