# Project Structure

## 📁 Cấu trúc dự án tổng quan

```
source_laravel_10/
├── app/                          # Application logic
│   ├── Console/                  # Artisan commands
│   │   ├── Commands/
│   │   │   ├── CreateAdminCommand.php    # Create admin user
│   │   │   ├── MakeFeatured.php          # Generate CRUD files
│   │   │   └── GenerateSitemap.php       # Generate sitemap
│   │   └── Kernel.php
│   ├── Events/                   # Event classes
│   │   ├── CateNew/
│   │   ├── CateProduct/
│   │   ├── News/
│   │   ├── Product/
│   │   └── Slider/
│   ├── Exceptions/               # Exception handling
│   │   └── Handler.php
│   ├── Helpers/                  # Helper functions
│   │   ├── CartHelper.php
│   │   ├── CateHelper.php
│   │   ├── Language.php
│   │   └── MenuHelper.php
│   ├── Http/                     # HTTP layer
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Admin controllers
│   │   │   │   ├── BaseController.php
│   │   │   │   ├── NewsController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CateProductController.php
│   │   │   │   ├── CateNewController.php
│   │   │   │   ├── PageController.php
│   │   │   │   ├── SliderController.php
│   │   │   │   ├── MenuController.php
│   │   │   │   ├── FeedbackController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── AnalyticController.php
│   │   │   ├── Frontend/          # Frontend controllers
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── NewsController.php
│   │   │   │   ├── PageController.php
│   │   │   │   └── ContactController.php
│   │   │   └── Controller.php     # Base controller
│   │   ├── Middleware/            # Custom middleware
│   │   │   ├── Authenticate.php
│   │   │   ├── CheckAuth.php
│   │   │   └── ...
│   │   ├── Requests/              # Form request validation
│   │   │   ├── Admin/
│   │   │   │   ├── NewsRequest.php
│   │   │   │   ├── ProductRequest.php
│   │   │   │   ├── CategoryRequest.php
│   │   │   │   └── ...
│   │   │   └── Login/
│   │   └── Kernel.php
│   ├── Listeners/                # Event listeners
│   │   ├── CateNew/
│   │   │   └── ClearCateNewCache.php
│   │   ├── CateProduct/
│   │   │   └── ClearCateProductCache.php
│   │   ├── News/
│   │   │   └── ClearNewsCache.php
│   │   ├── Product/
│   │   │   └── ClearProductCache.php
│   │   └── Slider/
│   │       └── ClearSliderCache.php
│   ├── Models/                   # Eloquent models
│   │   ├── Analytic.php
│   │   ├── CateNew.php
│   │   ├── CateProduct.php
│   │   ├── News.php
│   │   ├── Product.php
│   │   ├── Page.php
│   │   ├── Slider.php
│   │   ├── Menu.php
│   │   ├── Feedback.php
│   │   ├── User.php
│   │   └── ...
│   ├── Providers/                # Service providers
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   ├── Services/                 # Business logic services
│   │   ├── DataRemovalService.php    # Data removal with cleanup
│   │   ├── ImageService.php          # Image processing service
│   │   └── ModelToggleService.php    # Status & order management
│   └── Traits/                   # Reusable traits
│       ├── DataRemovalTrait.php      # Standardized data removal
│       ├── ImageHandlerTrait.php     # Centralized image processing
│       └── SlugHandlerTrait.php      # Auto slug generation
├── bootstrap/                    # Framework bootstrap files
│   ├── app.php
│   └── cache/
├── config/                       # Configuration files
│   ├── app.php                   # App configuration
│   ├── auth.php                  # Authentication config
│   ├── cache.php                 # Cache configuration
│   ├── database.php              # Database & Redis config
│   ├── filesystems.php           # Storage configuration
│   ├── queue.php                 # Queue configuration
│   ├── session.php               # Session configuration
│   └── ...
├── database/                     # Database files
│   ├── factories/                # Model factories
│   │   └── UserFactory.php
│   ├── migrations/               # Database migrations
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── create_tp_products_table.php
│   │   ├── create_tp_cate_products_table.php
│   │   ├── create_tp_news_table.php
│   │   ├── create_tp_cate_news_table.php
│   │   ├── create_tp_pages_table.php
│   │   ├── create_tp_sliders_table.php
│   │   └── ...
│   └── seeders/                  # Database seeders
│       ├── CategoryNewSeeder.php
│       ├── CategoryProductSeeder.php
│       ├── DatabaseSeeder.php
│       └── ...
├── docs/                         # Documentation
│   ├── _coverpage.md             # Docsify cover page
│   ├── _sidebar.md               # Docsify sidebar
│   ├── index.html                # Docsify main page
│   ├── README.md                 # Main documentation
│   ├── architecture.md           # Architecture overview
│   ├── configuration.md          # Configuration guide
│   ├── project-structure.md      # This file
│   ├── installation.md           # Installation guide
│   ├── content-management.md     # Content management guide
│   └── traits.md                 # Traits documentation
├── public/                       # Public web files
│   ├── admin/                    # Admin panel assets
│   │   ├── css/                  # Admin stylesheets
│   │   ├── js/                   # Admin JavaScript
│   │   ├── images/               # Admin images
│   │   └── libs/                 # Third-party libraries
│   ├── ckeditor/                 # CKEditor files
│   ├── vendor/                   # Published vendor assets
│   ├── index.php                 # Entry point
│   ├── favicon.ico
│   └── robots.txt
├── resources/                    # Application resources
│   └── views/                    # Blade templates
│       ├── admin/                # Admin panel views
│       │   ├── layouts/          # Admin layouts
│       │   ├── modules/          # Module-specific views
│       │   │   ├── news/         # News management views
│       │   │   ├── products/     # Product management views
│       │   │   ├── pages/        # Page management views
│       │   │   ├── sliders/      # Slider management views
│       │   │   └── ...
│       │   └── partials/         # Shared admin partials
│       ├── auth/                 # Authentication views
│       ├── frontend/             # Public website views
│       └── errors/               # Error pages
├── routes/                       # Route definitions
│   ├── api.php                   # API routes
│   ├── channels.php              # Broadcast channels
│   ├── console.php               # Console commands
│   └── web.php                   # Web routes
├── storage/                      # Storage files
│   ├── app/                      # Application storage
│   ├── framework/                # Framework storage
│   │   ├── cache/
│   │   ├── sessions/
│   │   ├── testing/
│   │   └── views/
│   └── logs/                     # Application logs
├── tests/                        # Test files
│   ├── Feature/                  # Feature tests
│   ├── Unit/                     # Unit tests
│   ├── CreatesApplication.php
│   └── TestCase.php
├── .env                          # Environment configuration
├── .env.example                  # Environment example
├── artisan                       # Artisan command line
├── composer.json                 # Composer dependencies
├── composer.lock                 # Composer lock file
├── package.json                  # NPM dependencies
├── README.md                     # Project README
└── phpunit.xml                   # PHPUnit configuration
```

## 🎯 Key Directories Explained

### `/app` Directory
**Core application logic và business rules**

- **Console/Commands**: Custom Artisan commands
- **Events**: Application events
- **Http/Controllers**: Request handling logic
- **Listeners**: Event listeners cho cache clearing
- **Models**: Eloquent ORM models
- **Services**: Business logic separation
- **Traits**: Reusable code components

### `/resources/views` Directory  
**Presentation layer (Blade templates)**

```
resources/views/
├── admin/                    # Admin panel interface
│   ├── layouts/
│   │   ├── master.blade.php  # Main admin layout
│   │   └── partials/         # Header, sidebar, footer
│   └── modules/              # Feature-specific views
│       ├── news/             # News management
│       │   ├── list.blade.php
│       │   ├── add.blade.php
│       │   └── edit.blade.php
│       ├── products/         # Product management  
│       └── ...
└── frontend/                 # Public website
    ├── layouts/
    ├── modules/
    └── partials/
```

### `/config` Directory
**System configuration files**

- **database.php**: Database connections, Redis setup
- **cache.php**: Cache drivers và TTL settings
- **filesystems.php**: File storage configuration
- **queue.php**: Queue connections và drivers

### `/database` Directory
**Database schema và data**

- **migrations**: Database structure changes
- **seeders**: Sample data insertion
- **factories**: Test data generation

### `/public` Directory
**Publicly accessible files**

- **admin/**: Admin panel assets (CSS, JS, images)
- **vendor/**: Published package assets
- **index.php**: Application entry point

## 🏗️ Architecture Patterns

### MVC Pattern
```
Request → Router → Controller → Model → Database
                ↓
            View (Blade) ← Response
```

### Service Layer Pattern
```
Controller → Service → Model → Database
         ↑
    Form Request (Validation)
```

### Trait-based Architecture
```
Controller uses:
├── ImageHandlerTrait    # Image processing
├── DataRemovalTrait     # Data cleanup
└── SlugHandlerTrait     # URL slug generation
```

## 📋 Naming Conventions

### Files và Directories
- **Controllers**: `PascalCase` + `Controller` suffix
- **Models**: `PascalCase` (singular)
- **Migrations**: `snake_case` with descriptive name
- **Views**: `kebab-case.blade.php`
- **Assets**: `kebab-case`

### Database Tables
- **Prefix**: `tp_` (table prefix)
- **Format**: `tp_table_names` (plural, snake_case)
- **Examples**: `tp_products`, `tp_cate_products`, `tp_news`

### Code Structure
- **Classes**: `PascalCase`
- **Methods**: `camelCase`
- **Variables**: `snake_case`
- **Constants**: `SCREAMING_SNAKE_CASE`

## 🔄 Development Workflow

### Adding New Feature
1. **Migration**: `php artisan make:migration create_table_name`
2. **Model**: `php artisan make:model ModelName`
3. **Controller**: `php artisan make:controller Admin/ModelController`
4. **Request**: `php artisan make:request Admin/ModelRequest`
5. **Views**: Create in `resources/views/admin/modules/`
6. **Routes**: Add to `routes/web.php`

### Custom Artisan Command
```bash
# Generate full CRUD (Migration + Model + Controller + Request)
php artisan make:featured FeatureName
```

### Asset Management
```bash
npm run dev      # Development build
npm run build    # Production build  
npm run watch    # Watch for changes
```

## 📦 Dependencies

### Core Dependencies
```json
{
  "php": "^8.1",
  "laravel/framework": "^10.10",
  "predis/predis": "^3.2",
  "intervention/image": "^2.3",
  "spatie/laravel-sitemap": "^7.3",
  "unisharp/laravel-filemanager": "^2.10"
}
```

### Development Dependencies
```json
{
  "barryvdh/laravel-debugbar": "^3.15",
  "laravel/pint": "^1.0",
  "phpunit/phpunit": "^10.1"
}
```
