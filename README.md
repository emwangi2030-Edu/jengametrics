# JengaMetrics

JengaMetrics is a Laravel-based construction project management platform. It brings project setup, quantity planning, labour operations, procurement tracking, and reporting into one system so teams can manage construction work from planning through execution.

This README is written for two audiences:

- Product and operations users who need to understand what the system does
- Developers who need to understand how to run and maintain it

## Product Overview

JengaMetrics is built around the idea that each primary user manages one or more construction projects and can invite sub-users with limited project access and role-based permissions.

The application supports the following core workflows:

- Create projects through a multi-step project wizard
- Review project details in a dashboard
- Track project completion using project steps and progress indicators
- Build and manage Bills of Quantities (BoQ)
- Generate and review Bills of Materials (BoM)
- Manage workers, attendance, payments, and labour tasks
- Create worker groups and assign tasks to groups or individuals
- Manage materials, suppliers, and requisitions
- View procurement and wage-related reports
- Manage sub-accounts and project-specific access
- Support admin users with a separate dashboard flow

## Main User Types

### Primary User

The primary user creates and owns projects, manages sub-users, and controls which projects a sub-user can access.

### Sub-User

A sub-user operates under a primary account. Their visibility and actions depend on the permissions and project access granted to them.

### Admin User

An admin user is handled separately from project-based users. Admin accounts are intended for background or administrative oversight rather than normal project creation and day-to-day project execution.

## Main Functional Modules

### Project Setup

Projects are created through a guided wizard. The wizard collects data in steps and presents confirmation data before project creation is finalized.

Primary code areas:

- `app/Http/Controllers/ProjectWizardController.php`
- `app/Http/Controllers/ProjectController.php`
- `resources/views/wizard`

### Dashboard And Progress Tracking

The dashboard is the main project summary page. It surfaces project context, project duration, progress, and project step completion.

Primary code areas:

- `app/Http/Controllers/DashboardController.php`
- `resources/views/dashboard.blade.php`
- `resources/views/dashboard_admin.blade.php`

### BoQ And BoM

The BoQ workflow manages quantity documents, sections, levels, and items. The BoM workflow is used to surface materials and labour information derived from project items.

Primary code areas:

- `app/Http/Controllers/BqDocumentController.php`
- `app/Http/Controllers/BqLevelController.php`
- `app/Http/Controllers/BqSectionController.php`
- `app/Http/Controllers/BqItemController.php`
- `app/Http/Controllers/BOMController.php`

### Labour Management

The labour side of the application supports workers, attendance, wage tracking, task assignment, worker grouping, and task completion monitoring.

Primary code areas:

- `app/Http/Controllers/WorkerController.php`
- `app/Http/Controllers/AttendanceController.php`
- `app/Http/Controllers/PaymentController.php`
- `app/Http/Controllers/LabourTaskController.php`

### Materials, Suppliers, And Requisitions

These modules support inventory management, material usage, supplier records, requisition workflows, and cost-related tracking.

Primary code areas:

- `app/Http/Controllers/MaterialController.php`
- `app/Http/Controllers/SupplierController.php`
- `app/Http/Controllers/RequisitionController.php`
- `app/Http/Controllers/CostTrackingController.php`
- `app/Http/Controllers/PurchasesReportController.php`
- `app/Http/Controllers/WagesReportController.php`

### User And Access Management

The application supports account updates, authentication, sub-account management, and project-based access restrictions.

Primary code areas:

- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/SubAccountController.php`
- `routes/auth.php`

## Technology Stack

JengaMetrics is currently built with:

- PHP 8.2+
- Laravel 11
- Blade templates for server-rendered views
- Eloquent ORM for database interaction
- PHPUnit for automated testing
- Laravel Breeze for authentication scaffolding

## Composer Dependencies

The following dependencies are currently declared in `composer.json`.

### Runtime Dependencies

- `php ^8.2`
- `laravel/framework ^11.9`
- `laravel/passport ^12.3`
- `laravel/sanctum ^4.0`
- `laravel/tinker ^2.9`
- `symfony/console ^7.3`

### Development Dependencies

- `fakerphp/faker ^1.23`
- `laravel/breeze ^2.1`
- `laravel/pint ^1.13`
- `laravel/sail ^1.26`
- `mockery/mockery ^1.6`
- `nunomaduro/collision ^8.0`
- `phpunit/phpunit ^11.0.1`

## Project Structure

The codebase follows a standard Laravel layout.

- `app/Http/Controllers` contains request and workflow logic
- `app/Models` contains Eloquent models
- `app/Http/helpers.php` contains globally autoloaded helper functions
- `resources/views` contains Blade templates
- `routes/web.php` contains the main application routes
- `routes/auth.php` contains authentication routes
- `database/migrations` contains schema definitions
- `tests` contains the automated test suite

## Local Setup

### 1. Install Dependencies

```bash
composer install
```

### 2. Create The Environment File

```bash
copy .env.example .env
```

If you are not on Windows, use the equivalent shell command for your environment.

### 3. Generate The Application Key

```bash
php artisan key:generate
```

### 4. Configure The Database

Update the database values in `.env`:

- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Link Storage If The Application Uses Public Uploads

```bash
php artisan storage:link
```

### 7. Start The Application

```bash
php artisan serve
```

## Setup On A Different Server

Use this when deploying the same codebase on staging or production.

### 1. Server Requirements

- PHP 8.2+
- Composer 2+
- PostgreSQL or MySQL (matching your `.env` settings)
- Web server (Nginx or Apache)
- Access to run Artisan commands

### 2. Pull The Code

```bash
git clone <your-repository-url> /var/www/jengametrics
cd /var/www/jengametrics
```

Or, if the app already exists on the server:

```bash
git pull origin <branch>
```

### 3. Install Production Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 4. Create And Configure Environment Variables

```bash
cp .env.example .env
```

Set server-specific values in `.env`:

- `APP_NAME`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `FILESYSTEM_DISK`
- `MAIL_*` values (if using mail features)

### 5. Generate App Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate --force
```

### 7. Create Storage Symlink

```bash
php artisan storage:link
```

### 8. Cache Laravel Config/Routes/Views

```bash
php artisan optimize
```

### 9. Set Folder Permissions

Ensure the web server user can write to:

- `storage/`
- `bootstrap/cache/`

Example on Linux:

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 10. Configure Web Server Document Root

Point the web server to the Laravel `public` directory, not the project root.

Example:

- `/var/www/jengametrics/public`

### 11. Queue And Scheduler (If Used)

Run queues and scheduler in the background:

```bash
php artisan queue:work
```

Cron entry (every minute):

```bash
* * * * * cd /var/www/jengametrics && php artisan schedule:run >> /dev/null 2>&1
```

### 12. Deployment Refresh Commands

After each `git pull`, run:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

### Notes

- Keep `.env` out of GitHub and set per server.
- Development and production should use different `.env` files.
- Back up your database before running migrations in production.

## Useful Commands

Run tests:

```bash
php artisan test
```

List routes:

```bash
php artisan route:list
```

Clear caches:

```bash
php artisan optimize:clear
```

Format PHP code:

```bash
./vendor/bin/pint
```

## Key Files For New Developers

- `routes/web.php`
- `routes/auth.php`
- `app/Http/helpers.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Http/Controllers/ProjectWizardController.php`
- `app/Http/Controllers/SubAccountController.php`
- `app/Http/Controllers/LabourTaskController.php`
- `app/Http/Controllers/BqSectionController.php`

## Intended Audience

JengaMetrics is intended for construction-focused teams such as:

- Project owners
- Project managers
- Site administrators
- Procurement teams
- Labour supervisors
- Finance and reporting staff
- System administrators

## Summary

JengaMetrics is a multi-module construction operations platform. From a product perspective, it centralizes project setup, progress tracking, labour coordination, materials management, procurement, and reporting. From a developer perspective, it is a Laravel 11 application structured around controllers, Blade views, Eloquent models, and route-driven workflows.
