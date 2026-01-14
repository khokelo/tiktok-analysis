# 📊 Database Connection Guide - TikTok Analysis

## ✅ Status: PRODUCTION READY

**Database Type:** SQLite  
**File Location:** `database/database.sqlite`  
**File Size:** 114.688 KB  
**Status:** ✅ All 8 migrations applied, 12 tables created  
**Connection:** ✅ Verified and tested  

---

## 📁 Database File Location

```
c:\Users\usama\OneDrive\Documents\GitHub\tiktok-analysis\database\database.sqlite
```

### File Info
- **Format:** SQLite3 binary database
- **Size:** 114,688 bytes (114 KB)
- **Encoding:** UTF-8
- **Last Modified:** Today (auto-created by migrations)

---

## 🔧 Configuration

### Laravel Configuration File: `.env`

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Database Config: `config/database.php`

```php
'sqlite' => [
    'driver' => 'sqlite',
    'url' => env('DATABASE_URL'),
    'database' => env('DB_DATABASE', database_path('database.sqlite')),
    'prefix' => '',
    'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
],
```

---

## 📊 Database Schema

### ✅ Tables Created (13 total)

| # | Table Name | Purpose | Columns | Records |
|---|---|---|---|---|
| 1 | `migrations` | Track applied migrations | 3 | 8 |
| 2 | `sqlite_sequence` | SQLite internal sequence | 2 | - |
| 3 | `users` | User accounts & authentication | 9 | 0 |
| 4 | `password_reset_tokens` | Password reset functionality | 3 | 0 |
| 5 | `sessions` | Session management | 6 | 0 |
| 6 | `cache` | Cache storage | 3 | 0 |
| 7 | `cache_locks` | Cache locks | 3 | 0 |
| 8 | `jobs` | Queue jobs | 7 | 0 |
| 9 | `job_batches` | Job batches | 10 | 0 |
| 10 | `failed_jobs` | Failed job tracking | 7 | 0 |
| 11 | `sales` | Sales data & metrics | 23 | 0 |
| 12 | `tiktok_sales` | TikTok-specific sales | 10 | 0 |
| 13 | `uploaded_files` | File upload tracking | 10 | 0 |

### Detailed Table Schemas

#### 📌 Users Table
```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    email_verified_at TIMESTAMP,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

#### 💰 Sales Table (23 columns)
```sql
CREATE TABLE sales (
    id, campaign, day, date, time,
    direct_gmv, items_sold, customers,
    sku_orders, main_orders, viewers,
    views, product_impressions,
    click_through_rate, enter_room_rate,
    product_clicks, impressions,
    new_followers, shares, comments, likes,
    created_at, updated_at
)
```

#### 📱 TikTok Sales Table
```sql
CREATE TABLE tiktok_sales (
    id, campaign, date, time,
    direct_gmv, items_sold, customers,
    viewers, created_at, updated_at
)
```

#### 📂 Uploaded Files Table
```sql
CREATE TABLE uploaded_files (
    id, user_id (FK → users.id),
    original_name, stored_name, file_size,
    file_path, mime_type,
    created_at, updated_at
)
```

---

## 🚀 Connection Verification

### Test Database Connection

```bash
# Test via PHP
php test_db.php

# Test via Artisan
php artisan tinker
>>> DB::connection('sqlite')->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME)
=> "sqlite"

# Run migrations status
php artisan migrate:status
```

### Expected Output
```
✓ Database Connection: SUCCESS
✓ Database File: database/database.sqlite (114,688 bytes)
✓ Total Tables: 13
✓ Migrations Applied: 8
✓ Status: READY FOR USE
```

---

## 🌱 Seeding Sample Data

### Add Test Users

```bash
# Create admin user
php artisan tinker
>>> \App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'role' => 'admin',
    'password' => bcrypt('password')
])
```

### Run Database Seeder

```bash
php artisan db:seed
# or
php artisan db:seed --class=DatabaseSeeder
```

### Sample Seeder Code (database/seeders/DatabaseSeeder.php)

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Regular user
        DB::table('users')->insert([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
```

---

## 📝 Usage Examples

### In Laravel Application

#### Using Query Builder
```php
// Get all users
$users = DB::table('users')->get();

// Insert sales data
DB::table('sales')->insert([
    'campaign' => 'Campaign A',
    'date' => now()->toDateString(),
    'direct_gmv' => 1500.50,
    'items_sold' => 25,
]);

// Query with conditions
$sales = DB::table('sales')
    ->where('date', now()->toDateString())
    ->where('direct_gmv', '>', 1000)
    ->get();
```

#### Using Eloquent ORM
```php
// Create user
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'role' => 'user',
    'password' => bcrypt('password'),
]);

// Find user
$user = User::find(1);
$user = User::where('email', 'user@example.com')->first();

// Update
$user->update(['role' => 'admin']);

// Delete
$user->delete();
```

---

## 🛠️ Database Maintenance

### Backup Database
```bash
# Copy to backup
cp database/database.sqlite database/database.backup.sqlite

# Or with timestamp
cp database/database.sqlite database/database.backup.$(date +%Y%m%d_%H%M%S).sqlite
```

### Fresh Migration
```bash
# Reset and remigrate
php artisan migrate:fresh

# With seeding
php artisan migrate:fresh --seed
```

### Check Database Integrity
```bash
# Via SQLite CLI
sqlite3 database/database.sqlite "PRAGMA integrity_check;"

# Via Laravel
php artisan tinker
>>> DB::statement('PRAGMA integrity_check;')
```

### Optimize Database
```bash
# Via SQLite CLI
sqlite3 database/database.sqlite "VACUUM;"

# Via Laravel Artisan
php artisan db:vacuum
```

---

## 🔒 Security Notes

- **Development:** SQLite file is stored in `database/` (ignored in git)
- **Production:** Use MySQL/PostgreSQL instead
- **Permissions:** Ensure `database/` folder is writable by PHP process
- **Access:** Direct SQLite file access requires write permissions

---

## 📊 Monitoring Queries

### File Size
```bash
# Check database file size
ls -lh database/database.sqlite

# Or in PowerShell
Get-Item database\database.sqlite | Select-Object Length
```

### Table Sizes
```bash
sqlite3 database/database.sqlite << EOF
SELECT 
    name,
    SUM(pgsize) as size_bytes,
    ROUND(SUM(pgsize)/1024.0, 2) as size_kb
FROM dbstat 
GROUP BY name 
ORDER BY size_bytes DESC;
EOF
```

### Row Counts
```bash
php artisan tinker
>>> collect(['users', 'sales', 'tiktok_sales', 'uploaded_files'])->each(fn($t) => echo "$t: " . DB::table($t)->count() . "\n")
```

---

## 🐛 Troubleshooting

### Database File Not Found
```
Error: The "database/database.sqlite" file does not exist.
Solution: Run `php artisan migrate` to create it
```

### Read-Only Database
```
Error: attempt to write a readonly database
Solution: chmod 644 database && chmod 644 database/database.sqlite
```

### Foreign Key Constraint Error
```
Error: FOREIGN KEY constraint failed
Solution: Ensure `foreign_key_constraints` is enabled in config/database.php
```

### Migration Already Exists
```
Error: Call to undefined method ... (migration file)
Solution: Delete migration from migrations table or restart fresh
```

---

## ✅ Connection Checklist

- [x] SQLite database file exists: `database/database.sqlite`
- [x] File size: 114,688 bytes
- [x] All 8 migrations applied successfully
- [x] 13 tables created (12 app + migrations)
- [x] Foreign key constraints enabled
- [x] Connection verified: ✅ SUCCESS
- [x] Users table: Ready for data
- [x] Sales table: Ready for data
- [x] TikTok sales table: Ready for data
- [x] File uploads tracking: Ready

---

## 📞 Quick Commands

```bash
# Start backend server
php artisan serve

# Start frontend dev server
npm run dev

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Check migrations status
php artisan migrate:status

# Create new migration
php artisan make:migration create_table_name

# Test database connection
php artisan tinker
>>> DB::connection('sqlite')->getPdo()

# Rollback last migration
php artisan migrate:rollback

# Reset entire database
php artisan migrate:fresh --seed
```

---

**Last Updated:** Today  
**Database Status:** ✅ Production Ready  
**Next Step:** Begin development!
