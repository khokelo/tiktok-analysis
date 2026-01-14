# Setup Instructions

Quick setup guide untuk TikTok Analysis dengan Laravel 12.

## Prerequisites Check

```bash
# Verify PHP version
php -v
# Expected: PHP 8.2 or higher

# Verify Composer
composer --version

# Verify Node.js
node --version
npm --version
```

## Installation Steps

### 1. Clone Repository

```bash
git clone https://github.com/khokelo/tiktok-analysis.git
cd tiktok-analysis
```

### 2. Install Dependencies

```bash
# Install PHP packages
composer install

# Install JavaScript packages
npm install --legacy-peer-deps
```

### 3. Configure Environment

```bash
# Copy environment file
cp .env.local .env

# If needed, generate new app key
php artisan key:generate
```

### 4. Database Setup

Database SQLite sudah tersedia di `database/database.sqlite`.

Jika ingin menggunakan MySQL:

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE tiktok_analysis;"

# Update .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tiktok_analysis
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations
php artisan migrate
```

### 5. Build Frontend Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

## Running Locally

### Start Development Servers

**Terminal 1: Frontend Development Server**
```bash
npm run dev
```
Output: 
- Local: http://localhost:5173
- Network: check console output

**Terminal 2: Backend Development Server**
```bash
php -S 127.0.0.1:8000 -t public
```
Access: http://localhost:8000

### Optional: Interactive Console

**Terminal 3:**
```bash
php artisan tinker
```

## Verification

### Check Installation

```bash
# List Laravel version
php artisan --version

# List environment
php artisan about

# Check routes
php artisan route:list
```

### Test Database Connection

```bash
# In php artisan tinker
> DB::connection()->getPdo()
=> PDOConnection Object
```

## Common Issues

### Port Already in Use

```bash
# Change PHP server port
php -S 127.0.0.1:8001 -t public

# Change Vite port
npm run dev -- --port 5174
```

### npm Peer Dependency Warning

This is expected. The `--legacy-peer-deps` flag handles compatibility.

### Database File Permissions

```bash
# Make database writable
chmod 666 database/database.sqlite
```

### Cache Issues

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:cache
```

## Project Structure

```
tiktok-analysis/
├── app/                    # Application code
│   ├── Http/
│   ├── Models/
│   ├── Console/
│   ├── Exceptions/
│   └── Providers/
├── bootstrap/              # Bootstrap files
├── config/                 # Configuration files
├── database/
│   ├── migrations/         # Database schemas
│   ├── seeders/            # Data seeders
│   ├── database.sqlite     # SQLite database
│   └── database.sql        # MySQL schema reference
├── public/                 # Web root
├── resources/
│   ├── css/                # Tailwind CSS
│   ├── js/                 # JavaScript/Vue
│   └── views/              # Blade templates
├── routes/                 # Route definitions
├── storage/                # Logs, uploads, cache
├── tests/                  # Test files
├── vendor/                 # Composer packages
└── node_modules/           # npm packages
```

## Next Steps

1. Read `README.md` for overview
2. Read `DEPLOYMENT.md` for deployment guide
3. Check `app/Http/Controllers/` for available controllers
4. Check `routes/web.php` for route definitions
5. Check `database/migrations/` for database schema

## Support

For issues or questions:
1. Check existing issues on GitHub
2. Create new issue with detailed description
3. Include error messages and steps to reproduce

## Useful Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:rollback    # Rollback migrations
php artisan db:seed             # Run seeders
php artisan tinker              # Interactive shell

# Cache & Config
php artisan cache:clear         # Clear cache
php artisan config:cache        # Cache config
php artisan route:cache         # Cache routes

# Generate Files
php artisan make:model Name           # Create model
php artisan make:controller Name      # Create controller
php artisan make:migration table_name # Create migration

# Development
php artisan serve               # Start dev server
npm run dev                     # Start Vite dev server
npm run build                   # Build for production

# Testing
php artisan test                # Run tests
php artisan test --coverage     # Run with coverage
```

## Version Information

- Laravel: 12.x
- PHP: 8.2+
- Node.js: 18+
- npm: 9+
- Vite: 5+
- Tailwind CSS: 3+

---

Last Updated: 2026-01-14
