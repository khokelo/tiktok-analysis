# 🔧 Installation & Setup Guide

## ✅ Prerequisites

Ensure you have installed:
- PHP 8.1+ (with extensions: pdo, pdo_mysql, mbstring)
- Composer
- MySQL/MariaDB or SQLite
- Node.js & npm (optional, for assets)
- Git

---

## 📦 Installation Steps

### Step 1: Project Setup

```bash
# Navigate to project directory
cd "c:\Users\Organizer1\OneDrive\Desktop\Projek\Laravel Project\tiktok-analysis"

# Install composer dependencies
composer install

# Copy environment file
cp .env.example .env
# Or on Windows:
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 2: Database Configuration

Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tiktok_analysis
DB_USERNAME=root
DB_PASSWORD=

# Or use SQLite:
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Create database:
```bash
# MySQL
mysql -u root -p
CREATE DATABASE tiktok_analysis;
exit;

# Or use Laravel in SQLite (no action needed)
```

### Step 3: Database Migrations

```bash
# Run all migrations
php artisan migrate

# Run specific migration
php artisan migrate --class=CreateUploadedFilesTable

# Rollback migrations (if needed)
php artisan migrate:rollback

# Reset database
php artisan migrate:reset
```

### Step 4: Create Admin User & Sample Data

```bash
# Run seeder for admin user
php artisan db:seed --class=AdminUserSeeder

# Run all seeders
php artisan db:seed
```

**Admin Account Created:**
```
Email: admin@email.com
Password: admin123
⚠️ Change immediately after first login!
```

### Step 5: Storage Configuration

```bash
# Create storage link for file uploads
php artisan storage:link

# On Windows (if link fails):
# Run PowerShell as admin:
New-Item -ItemType SymbolicLink -Path "public\storage" -Target "storage\app\public"

# Or configure in config/filesystems.php:
# Set FILESYSTEM_DRIVER=public
```

### Step 6: Start Development Server

```bash
# Start Laravel server (port 8000)
php artisan serve

# Or specify different port
php artisan serve --port=9000

# With Vite dev server (for assets)
npm run dev
# In another terminal:
php artisan serve
```

Server will be available at:
```
http://localhost:8000
http://127.0.0.1:8000
```

---

## ✨ Post-Installation Checklist

- [ ] Database created
- [ ] Migrations completed successfully
- [ ] Seeders executed
- [ ] Storage link created
- [ ] Server running
- [ ] Can access http://localhost:8000
- [ ] Can login with admin@email.com / admin123
- [ ] Dashboard loads without errors
- [ ] Charts display correctly
- [ ] Can create/edit/delete users
- [ ] Can upload CSV files

---

## 📝 Environment Variables

Key `.env` settings:

```env
# Application
APP_NAME=TikTok-Analysis
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tiktok_analysis
DB_USERNAME=root
DB_PASSWORD=

# Mail (optional)
MAIL_DRIVER=log
MAIL_FROM_ADDRESS=admin@example.com

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file

# Queue (optional)
QUEUE_CONNECTION=sync
```

---

## 🐛 Common Installation Issues

### Issue 1: "No application encryption key has been specified"
```bash
# Solution
php artisan key:generate
```

### Issue 2: "SQLSTATE[HY000]: General error"
```bash
# Check database connection in .env
# Test connection:
php artisan tinker
>>> DB::connection()->getPdo()
```

### Issue 3: "Migrations pending"
```bash
# Run migrations
php artisan migrate

# Check status
php artisan migrate:status
```

### Issue 4: "Class not found" error
```bash
# Regenerate autoloader
composer dump-autoload

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Issue 5: "Permission denied" for storage
```bash
# Windows - Run as administrator
# Linux/Mac:
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### Issue 6: "Module not found" (Node modules)
```bash
# Install npm dependencies (if using Vite)
npm install

# Build assets
npm run build
```

### Issue 7: "Storage symlink not working"
```bash
# Check storage link exists
# If not, create manually:
php artisan storage:link

# Or verify in web.php storage/app/public is accessible
```

---

## 🚀 Verification Steps

### Test 1: Application Access
```bash
# Open browser
http://localhost:8000

# Should redirect to login or welcome page
```

### Test 2: Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo()
>>> User::count()  # Should show user count
>>> exit
```

### Test 3: Admin Login
```
Email: admin@email.com
Password: admin123
Expected: Dashboard page
```

### Test 4: Admin Panel Access
```
URL: http://localhost:8000/admin
Expected: Admin dashboard with charts
```

### Test 5: Routes
```bash
php artisan route:list | grep admin
# Should show admin routes
```

---

## 📋 Installed Packages

### Core Packages
- `laravel/framework` - Laravel framework
- `laravel/tinker` - Interactive shell
- `laravel/fortify` - Authentication
- `laravel/sanctum` - API tokens

### Database
- `doctrine/dbal` - Database abstraction
- `illuminate/database` - Database ORM

### File Handling
- `league/csv` - CSV parsing

### Development
- `laravel/pint` - Code style
- `phpunit/phpunit` - Testing
- `mockery/mockery` - Mocking library

---

## 🔄 Regular Maintenance

### Daily
```bash
# Check logs
tail -f storage/logs/laravel.log

# Monitor database
# Regular backups recommended
```

### Weekly
```bash
# Clear caches
php artisan cache:clear

# Clear temp files
php artisan tinker
>>> DB::table('sessions')->delete()
```

### Monthly
```bash
# Update dependencies
composer update

# Run tests
php artisan test

# Database optimization
# (Backup first!)
php artisan db:optimize (in newer versions)
```

---

## 📊 Performance Optimization

### Enable Caching
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### Optimize Autoloader
```bash
# Optimize class autoloading
composer install --optimize-autoloader --no-dev
```

### Database
```bash
# Add indexes (already done in migrations)
# Monitor slow queries in logs
```

---

## 🔐 Security Hardening

### Change Default Credentials
```bash
# Login as admin, go to profile and change password
# Use strong password!
```

### Setup HTTPS (Production)
```bash
# Get SSL certificate (Let's Encrypt)
# Configure in web server (Nginx/Apache)
```

### Restrict File Upload Size
```env
# .env
APP_UPLOAD_LIMIT=10240  # 10MB
```

### Configure CORS (if needed)
```php
// config/cors.php
'allowed_origins' => ['http://localhost:3000'],
'allowed_methods' => ['*'],
```

---

## 📦 Docker Setup (Optional)

If you prefer Docker:

```dockerfile
# Dockerfile
FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    composer \
    mysql-client \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY . .

RUN composer install
RUN php artisan key:generate
RUN php artisan migrate

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
```

```bash
# Build and run
docker build -t tiktok-analysis .
docker run -p 8000:8000 tiktok-analysis
```

---

## ✅ Deployment Checklist

Before deploying to production:

- [ ] `.env` configured for production
- [ ] `APP_DEBUG=false` set
- [ ] `APP_ENV=production` set
- [ ] Database backed up
- [ ] Asset compiled (`npm run build`)
- [ ] Migrations tested
- [ ] Cache cleared
- [ ] Logs configured
- [ ] HTTPS enabled
- [ ] Firewall configured
- [ ] Backups scheduled
- [ ] Monitoring setup

---

## 🆘 Getting Help

If you encounter issues:

1. **Check logs**: `storage/logs/laravel.log`
2. **Read docs**: [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md)
3. **Check errors**: Browser DevTools → Console
4. **Test database**: `php artisan tinker`
5. **Clear cache**: `php artisan cache:clear`
6. **Reinstall**: `composer install && php artisan migrate`

---

## 📞 Support Resources

- Laravel Docs: https://laravel.com/docs
- Stack Overflow: Tag with `laravel`
- GitHub Issues: Check project repo
- Discord: Laravel community

---

## 🎉 Installation Complete!

Once installation is successful:

1. **Access dashboard**: http://localhost:8000/admin
2. **Login**: admin@email.com / admin123
3. **Change password**: Update from profile
4. **Start using**: Create users, upload files, monitor analytics

For next steps, read [QUICKSTART.md](QUICKSTART.md)

---

**Installation Guide Version**: 1.0.0
**Last Updated**: January 13, 2026

