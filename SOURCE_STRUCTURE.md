# TAI LIEU TONG HOP SOURCE CODE (ONE-STOP)

Tai lieu nay duoc cap nhat de muc tieu: chi can doc file nay la hieu duoc toan bo source code chinh cua du an.

Ngay doi chieu source: 2026-05-15
Framework: Laravel 10
PHP: >= 8.1 (theo composer.json)

---

## 1) Tong quan he thong

Day la du an Laravel theo huong CMS + website ban hang, gom 3 lop giao tiep chinh:

- Frontend (public): home, san pham, tin tuc, page dong, cart, checkout, contact.
- Admin: CRUD module noi dung + van hanh + phan quyen.
- API: mot so endpoint bo sung (vd fonts active cho CKEditor, Sanctum user endpoint).

Kien truc khong dung MVC thuan, ma da them:

- Repository layer (tach truy van du lieu khoi controller).
- Service layer (tach nghiep vu dung chung).
- Event/Listener (clear cache theo module).
- Traits + BaseController cho admin CRUD pattern.
- View Composer cho frontend data global.

---

## 2) Cong nghe va package chinh

Theo composer.json:

- laravel/framework ^10.10
- laravel/sanctum ^3.3
- genealabs/laravel-model-caching ^12.0
- predis/predis ^3.2
- intervention/image ^2.3
- spatie/laravel-sitemap ^7.3
- unisharp/laravel-filemanager ^2.10
- realrashid/sweet-alert ^7.3

Dev tools:

- barryvdh/laravel-debugbar
- phpunit/phpunit ^10.1
- laravel/pint

---

## 3) Cau truc thu muc cap cao

```text
source_laravel/
|-- app/
|-- bootstrap/
|-- config/
|-- database/
|-- public/
|-- resources/
|-- routes/
|-- storage/
|-- tests/
|-- artisan
|-- composer.json
|-- phpunit.xml
`-- SOURCE_STRUCTURE.md
```

---

## 4) Request lifecycle va diem vao he thong

### 4.1 HTTP lifecycle

1. Web server vao public/index.php.
2. Khoi tao app qua bootstrap/app.php.
3. Bind kernel:
   - App\Http\Kernel
   - App\Console\Kernel
   - App\Exceptions\Handler
4. App\Http\Kernel ap dung global middleware + middleware group + aliases.
5. Route duoc tai qua RouteServiceProvider -> routes/web.php va routes/api.php.

### 4.2 Middleware aliases quan trong

Trong app/Http/Kernel.php:

- checkAuth: bao ve admin area.
- visit: track truy cap.
- admin.level, role, permission: RBAC middleware.

### 4.3 Console lifecycle

Trong app/Console/Kernel.php:

- Auto load command trong app/Console/Commands.
- Dang ky them command cu the qua $commands.
- Lich schedule hien de trong (chua co cron job nghiep vu).

---

## 5) Ban do route

### 5.1 Web route tong

routes/web.php la file aggregator:

- include routes/utilities.php
- include routes/auth/login.php
- include frontend:
  - routes/frontend/home.php
  - routes/frontend/cart.php
  - routes/frontend/dynamic.php
- include admin group:
  - prefix admin
  - name admin.
  - middleware checkAuth
  - require 26 file module trong routes/admin/*.php

### 5.2 Frontend route

- home.php: trang chu, lien he, subscribe, trang 404.
- cart.php: cart + checkout flow.
- dynamic.php: bat route dong /{slug}.html -> Frontend\RouteController@resolve.

### 5.3 Dynamic route flow

SlugResolutionService tim slug theo chain:

1. page
2. product
3. news
4. cate_product
5. cate_news

Sau do Frontend\RouteController dispatch sang controller tuong ung.

### 5.4 Utilities route

routes/utilities.php co:

- switch language: lang/{locale}
- serve sitemap: /sitemap.xml
- custom file manager API: admin/files/* (middleware web, auth, permission:system.view)

### 5.5 API route

routes/api.php:

- GET /api/user (sanctum)
- GET /api/fonts/active (cho CKEditor font list)

---

## 6) Cau truc chi tiet trong app/

```text
app/
|-- Console/
|   `-- Commands/
|-- Events/
|-- Exceptions/
|-- Handlers/
|-- Helpers/
|-- Http/
|   |-- Controllers/
|   |-- Middleware/
|   `-- Requests/
|-- Listeners/
|-- Mail/
|-- Models/
|-- Providers/
|-- Repositories/
|-- Services/
|-- Traits/
`-- View/Composers/
```

### 6.1 Controllers

Tong so controller dang co: 46 file

- Admin: quan tri module (analytics, product, news, page, menu, slider, brand, feedback, feature, chat, order, user, role, mail config/template, gallery, site setting, ...).
- Frontend: home, product, news, page, cart, checkout, contact, search, route dynamic.
- Auth: login, profile, email verification, password reset OTP flow.
- API: comment, chat.

### 6.2 Base admin pattern

app/Http/Controllers/Admin/BaseController.php:

- Build duong dan view theo module: admin.modules.{module}
- Helper redirect route admin
- Khoi tao service dung chung:
  - ImageService
  - DataRemovalService
  - ModelToggleService
- Dung trait:
  - DataRemovalTrait
  - ImageHandlerTrait
  - SlugHandlerTrait

### 6.3 Middleware custom

- CheckAuth
- CheckRole
- CheckPermission
- CheckAdminLevel
- Language
- Visit

### 6.4 Providers

- AppServiceProvider:
  - Paginator::useBootstrapFive()
  - bind View::composer('frontend.*', FrontendComposer::class)
- AdminServiceProvider:
  - custom blade directives: @hasRole, @hasPermission, @hasAnyRole, @hasAnyPermission
- RepositoryServiceProvider:
  - bind interface -> repository implementation
- EventServiceProvider:
  - map event -> listener cho clear cache
- MailConfigServiceProvider:
  - hien khong co bootstrap/register custom logic

### 6.5 View composer

FrontendComposer inject du lieu global cho toan bo frontend.*:

- system config
- menu tree
- slider ads
- category product/news
- footer pages
- cart_count
- products_hot

---

## 7) Repository va Service layer

### 7.1 Repository

app/Repositories gom:

- Interfaces/*
- Eloquent/*
- MailTemplateRepository.php
- MailTemplateRepositoryInterface.php

Eloquent/BaseRepository.php cung cap:

- CRUD co ho tro ID hoac UUID
- timestamp prepare
- UUID auto gen neu thieu
- filter + paginate co search/sort
- update status/order
- delete/find by UUID list
- helper tao slug unique

### 7.2 Services (17 file)

- CacheService
- CartService
- CheckoutService
- CommentService
- DataRemovalService
- HomeService
- ImageService
- MailConfigService
- MailTemplateService
- ModelToggleService
- NewsService
- ProductService
- RateLimitService
- SearchService
- SiteSettingService
- SlugResolutionService
- SlugService

Noi bat:

- RateLimitService: factory methods theo use case (forLogin, forContact, forCart, forCheckout, forContent).
- CartService: luu gio hang trong session, tinh tong so luong/tong tien.
- CheckoutService: transaction tao shipping + order status + order products + gui mail thong bao.
- MailConfigService: lay config mail active tu DB, co fallback config mail.*.

---

## 8) Event/Listener va cache invalidation

Co event-listener theo module de clear cache sau CRUD:

- Brand
- CateNew
- CateProduct
- Feature
- Gallery
- Menu
- News
- Page
- Product
- Slider
- Feedback/FeedBack (xem muc van de da xac minh)

Y nghia:

- Giu controller gon, khong hard-code clear cache tai tung action.
- De mo rong khi can bo sung queue/notification sau nay.

---

## 9) Domain model va database

### 9.1 Model chinh

- Content: Product, CateProduct, News, CateNew, Page, Menu, Slider, Brand, Feature, Gallery
- Tuong tac: FeedBack, Contact, Comment, ChatSession, ChatMessage
- Ban hang: OrderShipping, OrderStatus, OrderProduct, ProductSetting
- He thong: User, Role, Permission, System, SiteSetting, MailConfig, MailTemplate, Font, Analytic

### 9.2 Migration map (32 file)

Nhom bang chinh:

- Core Laravel: users, password_reset_tokens, failed_jobs, personal_access_tokens
- CMS/Product: tp_cate_products, tp_products, tp_cate_news, tp_news, tp_pages, tp_menus, tp_sliders
- Business: tp_order_shipping, tp_order_status, tp_order_product, product_settings
- Interaction: tp_contacts, tp_feedback, tp_comments, chat tables
- ACL: tp_roles, tp_permissions, role_permission, role_user, user_permission
- System config: tp_systems, tp_analytics, tp_brands, tp_features, tp_fonts, tp_galleries, tp_mail_configs, tp_mail_templates, site_settings

---

## 10) View layer

resources/views gom:

- admin/ (master, partials, ajax, modules)
- frontend/
- auth/
- errors/
- vendor/

Pattern admin theo module:

- resources/views/admin/modules/{module}/list.blade.php
- resources/views/admin/modules/{module}/detail.blade.php

---

## 11) Auth, security, permission

- Login route custom:
  - GET /admintv
  - POST /admintv_post_login
  - GET /admintv_logout
- LoginController co rate-limit theo IP qua RateLimitService.
- User chua verify email thi khong dang nhap duoc admin.
- RBAC middleware bao ve route admin theo permission string.
- Blade directives trong AdminServiceProvider de an/hien UI theo role/permission.

---

## 12) Mail va notification flow

- Mailable classes:
  - AlertContact
  - AlertOrder
  - EmailVerificationOtp
  - PasswordResetOtpMail
- Password reset dang su dung OTP flow qua phone identifier + gui OTP qua email.
- Checkout gui mail cho:
  - admin (tp_systems.email_alert)
  - customer (shipping.email)

---

## 13) Artisan commands custom

Trong app/Console/Commands:

- admin:create: tao tai khoan admin.
- make:featured {name}: scaffold migration + model + admin controller + request.
- sitemap:generate: tao public/sitemap.xml bang Spatie Sitemap.
- app:check-permissions: command kiem tra permission mau.

---

## 14) Testing hien tai

So test file hien co: 5

- tests/Feature/ExampleTest.php
- tests/Unit/ExampleTest.php
- tests/Unit/Admin/CateNewControllerTest.php
- tests/TestCase.php
- tests/CreatesApplication.php

Danh gia:

- Coverage thap so voi do rong module.
- Chua thay test cho slug dynamic route, checkout transaction, permission middleware, event/listener cache flow.

---

## 15) Van de da doi chieu trong source (quan trong)

Nhung diem duoi day da thay trong code, can luu y khi maintain/deploy:

1. Khong nhat quan ten FeedBack vs Feedback.
   - Folder dang la app/Events/FeedBack va app/Listeners/FeedBack.
   - Nhieu noi import dang Feedback (chu thuong/hoa khac).
   - Tren Linux co nguy co class not found do filesystem case-sensitive.

2. RepositoryServiceProvider thieu import cho UserRepositoryInterface va UserRepository.
   - File dang bind 2 class nay nhung chua use tuong ung.

3. RepositoryServiceProvider import interface feedback voi ten FeedbackRepositoryInterface
   nhung class dang duoc dung o code la FeedBackRepositoryInterface.

4. FeedBackController::destroyAll() dang goi FeedbackChanged::dispatch(...)
   trong khi event class ton tai la FeedBackChanged.

5. ClearFeedBackCache import event App\Events\FeedBack\FeedbackChanged
   nhung method handle(FeedBackChanged $event) dung ten class khac.

6. Co mot so message/comment tieng Viet bi loi encoding trong mot so file.
   Nen chuan hoa UTF-8 toan bo de tranh loi UI/log.

Luu y: cac diem tren la issue code hien tai, khong phai issue cua tai lieu.

---

## 16) Checklist them module moi (de dung kien truc hien tai)

1. Tao migration + model.
2. Tao repository interface + implementation.
3. Bind vao RepositoryServiceProvider.
4. Tao FormRequest validate.
5. Tao Admin controller (uu tien ke thua BaseController).
6. Tao route file rieng trong routes/admin/ va require tu routes/web.php.
7. Tao view trong resources/views/admin/modules/{module}.
8. Neu co cache frontend/admin: tao Event + Listener clear cache.
9. Cap nhat permission seed + middleware permission.
10. Bo sung test Unit/Feature cho CRUD va route chinh.

---

## 17) Lenh van hanh co ban

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

Lenh bo sung huu ich:

```bash
php artisan admin:create
php artisan sitemap:generate
php artisan test
./vendor/bin/pint
```

---

## 18) Ket luan

Kien truc du an da theo huong module hoa ro rang, phu hop cho CMS + e-commerce medium scale:

- Route tach module
- Controller gon nhieu noi da dua nghiep vu sang Service/Repository
- Event/Listener cho cache invalidation
- RBAC middleware + blade directives

De he thong on dinh hon khi deploy production (dac biet Linux), uu tien xu ly nhat quan naming FeedBack/Feedback, chuan import trong providers, va tang test cho cac flow trong yeu.
