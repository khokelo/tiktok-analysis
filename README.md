# TikTok Analysis

Aplikasi analisis penjualan TikTok dengan Laravel 12 dan Vite.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- npm

## Setup Lokal

### 1. Clone & Install Dependencies

```bash
git clone https://github.com/khokelo/tiktok-analysis.git
cd tiktok-analysis

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install --legacy-peer-deps
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.local .env

# Generate application key (jika perlu)
php artisan key:generate
```

### 3. Database

Database menggunakan SQLite secara default untuk development. Database file sudah tersedia di `database/database.sqlite`.

### 4. Build Assets

```bash
npm run build
```

## Development

### Start Development Servers

Buka 2 terminal berbeda:

**Terminal 1 - Frontend (Vite)**
```bash
npm run dev
```
Akses: http://localhost:5173

**Terminal 2 - Backend (PHP)**
```bash
php -S 127.0.0.1:8000 -t public
```
Akses: http://localhost:8000

## Database Schema

Database schema tersedia di `database/database.sql` untuk MySQL reference.

### Tabel Utama

- `users` - User dan admin
- `sales` - Data penjualan utama
- `tiktok_sales` - Data penjualan TikTok
- `uploaded_files` - File upload tracking
- `sessions` - Session management
- `cache` - Cache storage

## Useful Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Database
php artisan migrate          # Run migrations
php artisan db:seed          # Run seeders
php artisan tinker           # Interactive shell

# Create new components
php artisan make:model Name          # Create model
php artisan make:controller Name     # Create controller
php artisan make:migration name      # Create migration
```

## Production Build

```bash
npm run build
```

## License

MIT
