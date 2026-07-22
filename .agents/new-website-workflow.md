# Quy trình tạo website mới – Trong 1 phiên chat

> Khi nhận yêu cầu *"Tạo website về chủ đề [X]"*, AI thực hiện tuần tự các bước dưới đây **mà không hỏi lại từng bước**.
> Mục tiêu: Sau 1 phiên chat, website chạy được với dữ liệu mẫu đầy đủ từ `db:seed`.

---

## Bước 1 – Thiết kế Database & Chạy Migration

### Trường bắt buộc mọi bảng nội dung:
```php
$table->id();
$table->string('uuid')->unique();
$table->string('name_vn');
$table->string('name_en')->nullable();
$table->string('slug_vn')->unique();
$table->string('slug_en')->unique()->nullable();
$table->string('image_vn')->nullable();
$table->string('image_en')->nullable();
$table->text('intro_vn')->nullable();
$table->text('intro_en')->nullable();
$table->longText('content_vn')->nullable();
$table->longText('content_en')->nullable();
$table->string('keyword_vn')->nullable();
$table->string('keyword_en')->nullable();
$table->text('description_vn')->nullable();
$table->text('description_en')->nullable();
$table->tinyInteger('status')->default(1);
$table->integer('stt')->default(0);
$table->timestamps();

// Index bắt buộc:
$table->index(['status', 'slug_vn']);
$table->index(['status', 'slug_en']);
$table->index(['status', 'stt']);
```

```bash
php artisan migrate
```

---

## Bước 2 – Viết Seeders & Chạy `db:seed`

### Danh sách Seeder cần tạo:

| Seeder | Nội dung tối thiểu |
|---|---|
| `SystemSeeder` | Tên công ty, phone, email, address, logo path, favicon, map URL embed, facebook, footer HTML |
| `MenuSeeder` | 5-7 menu items khớp với slug thực tế |
| `SliderSeeder` | 3-5 slides, ảnh lưu local `public/images/slider/slide_1.jpg` |
| `BrandSeeder` | 4-8 thương hiệu đối tác với logo local |
| `CateProductSeeder` | 3-5 danh mục, ít nhất 1 có `home=1` |
| `ProductSeeder` | 12-16 sản phẩm, 8 có `hot=1`, đầy đủ `price`, `price_old`, `uuid`, ảnh local |
| `CateNewSeeder` | 2-3 danh mục tin tức |
| `NewsSeeder` | 6-10 bài viết, đầy đủ `intro_vn`, `image_vn`, ảnh local |
| `PageSeeder` | Giới thiệu, Chính sách, Liên hệ — ít nhất 1 có `footer=1` |

### Nguyên tắc seeder QUAN TRỌNG:
- ✅ Nội dung **thực tế**, đúng chủ đề (KHÔNG dùng Lorem Ipsum)
- ✅ Ảnh mẫu lưu **local** (ví dụ: `public/images/product/slide_1.jpg`) và được gán đường dẫn đơn (ví dụ: `'image' => 'slide_1.jpg'`) trong seeders. Nhờ cơ chế fallback của `AutoImagePathsTrait`, hệ thống sẽ tự động tìm đúng thư mục tương ứng của module để hiển thị.
- ✅ `price` điền số thực (VD: 2500000), `price_old` nếu đang giảm giá
- ✅ `logo` trong SystemSeeder: `images/logo/logo.png` (relative path)
- ✅ `map` trong SystemSeeder: embed URL Google Maps đầy đủ

```bash
php artisan db:seed
# Hoặc reset toàn bộ:
php artisan migrate:fresh --seed
```

---

## Bước 3 – Backend (Model + Repository + Service)

```php
// Model:
class Product extends Model {
    use AutoImagePathsTrait; // Tự convert image → full URL (tự động xử lý cả ảnh phân cấp YYYY/MM và ảnh đơn fallback)

    public function getNameAttribute() {
        $locale = app()->getLocale();
        return $this->{'name_' . $locale} ?: $this->name_vn;
    }
    public function getSlugAttribute() { ... }
    public function getImageAttribute() { ... }
}
```

```php
// Repository Interface:
interface ProductRepositoryInterface extends BaseRepositoryInterface {}

// Repository Eloquent:
class ProductRepository extends BaseRepository implements ProductRepositoryInterface {}

// Đăng ký trong RepositoryServiceProvider:
$this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
```

---

## Bước 4 – Cập nhật FrontendComposer

Nếu cần thêm biến toàn cục mới, cập nhật `app/View/Composers/FrontendComposer.php`:

```php
return [
    'website'       => System::first(),
    'menu'          => Menu::with('children')->where('parent_id', 0)->orderBy('stt')->get(),
    'cate_product'  => CateProduct::where('status', 1)->where('parent_id', 0)->orderBy('stt')->get(),
    'footer_pages'  => Page::where('status', 1)->where('footer', 1)->orderBy('stt')->get(),
    'products_hot'  => Product::where('status', 1)->where('hot', 1)->orderBy('stt')->limit(10)->get(),
    // Thêm biến mới tại đây nếu cần
];
```

---

## Bước 5 – Tạo Frontend Blade Views

### Cấu trúc file bắt buộc:
```
resources/views/frontend/
├── master.blade.php
├── partials/
│   ├── head.blade.php          ← meta SEO, favicon, CSS link
│   ├── header.blade.php        ← topbar + logo + search + cart + nav
│   ├── header-mobi.blade.php   ← logo + cart icon + hamburger
│   ├── menumobi.blade.php      ← sidebar slide-in mobile
│   ├── footer.blade.php        ← 4 cột: info + links + contact + về chúng tôi
│   ├── slider.blade.php        ← loop $sliders
│   ├── buttons.blade.php       ← floating hotline + zalo + back-to-top
│   └── script.blade.php        ← JS slider autoplay, AJAX cart, pagination
└── modules/
    ├── home/index.blade.php
    ├── product/
    │   ├── category.blade.php
    │   └── detail.blade.php
    ├── news/
    │   ├── category.blade.php
    │   └── detail.blade.php
    ├── page/index.blade.php
    └── contact/index.blade.php
```

### Quy tắc CSS bắt buộc:
```css
/* Trong public/frontend/css/style.css */
:root {
    --primary-color: #...;   /* Màu chủ đạo theo thương hiệu */
    --accent-color: #...;    /* Màu nhấn */
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Inter', sans-serif;
    --border-radius: 8px;
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
}
/* Grid responsive */
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
/* Mobile: 2 cột cho news và products */
@media (max-width: 768px) {
    .grid-4, .grid-3 { grid-template-columns: repeat(2, 1fr); gap: 12px; }
}
```

---

## Bước 6 – Đăng ký Routes

```php
// routes/frontend/home.php
Route::get('/', [HomeController::class, 'index'])->name('web.home');
Route::get('/gio-hang', [CartController::class, 'index'])->name('web.cart');
Route::get('/lien-he', [ContactController::class, 'index'])->name('web.contact');
Route::get('/tim-kiem', [SearchController::class, 'index'])->name('web.search');

// routes/frontend/dynamic.php (resolve slug tự động)
Route::get('/{slug}', [RouteController::class, 'resolve'])->name('web.resolve');
```

---

## Bước 7 – Clear cache & kiểm tra

```bash
php artisan optimize:clear
php artisan view:clear
php artisan route:list   # Kiểm tra routes đã đăng ký đúng
```

---

## Checklist hoàn thành trong 1 chat

### Database & Seeders
- [ ] Migration tạo xong & chạy được
- [ ] SystemSeeder: phone, email, address, logo, map đầy đủ
- [ ] SliderSeeder: ảnh lưu local (không dùng URL CDN)
- [ ] CateProductSeeder: có ít nhất 1 category với `home=1`
- [ ] ProductSeeder: 8+ sản phẩm có `hot=1`, `price` thực tế
- [ ] NewsSeeder: 6+ bài viết
- [ ] PageSeeder: có trang với `footer=1`
- [ ] `php artisan db:seed` chạy thành công

### Backend
- [ ] Model + AutoImagePathsTrait + Dynamic Accessors
- [ ] Repository + binding trong ServiceProvider
- [ ] HomeService trả đủ: sliders, brands, category_product, hot_products, latest_news
- [ ] FrontendComposer cập nhật nếu cần biến mới

### Frontend
- [ ] 7 partials viết đầy đủ (head, header, header-mobi, footer, slider, buttons, script)
- [ ] master.blade.php inject đúng partials
- [ ] modules/home/index.blade.php với section slider + features + news + products + brands
- [ ] modules/product/ (category + detail)
- [ ] modules/news/ (category + detail)
- [ ] modules/contact/index.blade.php dùng `$website` toàn cục
- [ ] CSS: variables, grid 4/3/2 col, product-card, news-card, hover animations
- [ ] Mobile: 2 col cho products và news

### Routes & Deploy
- [ ] Routes đăng ký trong routes/frontend/
- [ ] Include vào routes/web.php
- [ ] `php artisan optimize:clear` chạy cuối cùng
- [ ] Kiểm tra trang chủ load được, slider hiển thị, sản phẩm hiển thị
