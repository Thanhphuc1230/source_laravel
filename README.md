# Laravel Admin Dashboard with Sweet Alert

A comprehensive Laravel admin dashboard with CRUD operations, SweetAlert notifications, and custom commands.

## Requirements

- PHP (8.0 or higher)
- Composer
- MySQL (or any other database you wish to use)

## Installation

1. **Clone the Project**
```bash
git clone https://github.com/Thanhphuc1230/source_laravel_10.git
```

2. **Navigate to the Project Directory**
```bash
cd source_laravel_10
```

3. **Install Dependencies**
```bash
composer install
```

4. **Create and Configure Environment File**
```bash
cp .env.example .env
```
Then edit the `.env` file with your database credentials and other settings.

5. **Generate Application Key**
```bash
php artisan key:generate
```

6. **Run Migrations**
```bash
php artisan migrate
```

7. **Create Dummy Data**
```bash
php artisan db:seed
```

8. **Start the Development Server**
```bash
php artisan serve
```

9. **Generate Sitemap**
```bash
php artisan sitemap:generate
```

10. **Access the Admin Panel**
Open your browser and go to `http://localhost:8000/admin`

## Custom Commands

### Create Full Feature Set
This project includes a custom command to quickly create all necessary files for a new feature:

```bash
php artisan make:featured ModelName
```

This generates:
- Migration file for table `tp_modelname`
- Model with proper table reference
- Admin controller with resource methods
- Form request for validation

Example:
```bash
php artisan make:featured Product
```

Creates:
- Migration: `*_create_tp_products_table.php`
- Model: `app/Models/Product.php`
- Controller: `app/Http/Controllers/Admin/ProductController.php`
- Request: `app/Http/Requests/Admin/ProductRequest.php`

## Features

- **Admin Dashboard**: Comprehensive admin interface
- **Sweet Alert Notifications**: User-friendly notifications
- **CRUD Operations**: Easy create, read, update, delete functionality
- **Form Validation**: Client and server-side validation with visual indicators
- **Service Architecture**: Clean code with services for image handling and data removal
- **Custom Commands**: Quickly scaffold new features

## File Structure

```
app/
├── Console/
│   └── Commands/           # Custom Artisan commands
├── Http/
│   ├── Controllers/
│   │   └── Admin/          # Admin controllers
│   └── Requests/
│       └── Admin/          # Form request validators
├── Models/                 # Eloquent models
├── Services/               # Service classes
│   ├── DataRemovalService.php
│   └── ImageService.php
└── ...
resources/
├── views/
│   └── admin/             # Admin templates
│       ├── modules/       # Feature-specific views
│       └── partials/      # Reusable components
└── ...
```

