# 🚀 QUICK START GUIDE - TikTok Analysis

## ⚡ 30 Seconds Setup

### 1️⃣ Prerequisites Check
```bash
# Check PHP version (need 8.2+)
php --version

# Check Node.js (need 18+)
node --version

# Check npm
npm --version
```

### 2️⃣ Dependencies Already Installed ✅
- ✅ Composer packages (83 total)
- ✅ NPM packages (157 total)
- ✅ Database migrated (8/8)
- ✅ Assets compiled with Vite

### 3️⃣ Start Development Servers

**Terminal 1 - Backend:**
```bash
cd c:\Users\usama\OneDrive\Documents\GitHub\tiktok-analysis
php artisan serve
```

Expected output:
```
INFO  Server running on [http://127.0.0.1:8000]
```

**Terminal 2 - Frontend:**
```bash
cd c:\Users\usama\OneDrive\Documents\GitHub\tiktok-analysis
npm run dev
```

Expected output:
```
VITE v7.3.1 ready in 1250 ms

➜ Local: http://localhost:5174/
```

### 4️⃣ Access Application
- 🌐 Open http://localhost:8000 in browser
- 🎨 Tailwind CSS styling ✅ applied
- ⚡ Hot module reload active

---

## 📊 Project Structure

```
tiktok-analysis/
├── app/
│   ├── Models/              # Database models
│   ├── Http/                # Controllers, middleware, requests
│   ├── Providers/           # Service providers (24 registered)
│   └── Console/             # Artisan commands
├── database/
│   ├── database.sqlite      # SQLite database ✅
│   ├── migrations/          # 8 migrations applied ✅
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   ├── css/                 # Tailwind CSS
│   └── js/                  # Frontend JS
├── routes/
│   ├── web.php              # Web routes (/, /test, /login)
│   ├── api.php              # API routes
│   └── auth.php             # Auth routes
├── public/
│   ├── build/               # Compiled assets (Vite) ✅
│   └── index.php            # Entry point
├── config/
│   ├── app.php              # App configuration
│   ├── database.php         # Database config (SQLite)
│   └── ...                  # Other configs
├── bootstrap/
│   ├── app.php              # App bootstrap ✅
│   └── providers.php        # Providers list (24 total) ✅
├── tests/                   # Test files
├── storage/                 # Logs, cache
├── vendor/                  # Composer packages
├── node_modules/            # NPM packages
├── .env                     # Environment variables
├── package.json             # NPM scripts
├── composer.json            # PHP dependencies
└── vite.config.js           # Vite configuration

```

---

## 🔧 Available Routes

| Route | Method | Description | Status |
|-------|--------|-------------|--------|
| `/` | GET | Welcome page with Tailwind CSS | ✅ Working |
| `/test` | GET | JSON test endpoint | ✅ Working |
| `/login` | GET | Login page | ✅ Working |
| `/register` | GET | Register page | ✅ Working |

---

## 💾 Database Info

**Type:** SQLite  
**File:** `database/database.sqlite` (114 KB)  
**Tables:** 13 (12 app + migrations)  
**Status:** ✅ All 8 migrations applied  

### Tables:
- `users` - User accounts
- `sales` - Sales metrics (23 columns)
- `tiktok_sales` - TikTok sales (10 columns)
- `uploaded_files` - File uploads
- `sessions` - Session management
- `cache` - Cache storage
- `jobs` - Queue jobs
- And more...

---

## 📦 NPM Scripts

```bash
# Development with hot reload
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

---

## 🛠️ Common Tasks

### Add Database Record
```bash
php artisan tinker
>>> DB::table('sales')->insert(['campaign' => 'Test', 'direct_gmv' => 100])
```

### Create New User
```bash
php artisan tinker
>>> App\Models\User::create(['name' => 'John', 'email' => 'john@test.com', 'password' => bcrypt('pass')])
```

### Run Migrations
```bash
php artisan migrate
```

### Fresh Database (Reset)
```bash
php artisan migrate:fresh --seed
```

### Create Controller
```bash
php artisan make:controller HomeController
```

### Create Model
```bash
php artisan make:model Product
```

---

## 🐛 Troubleshooting

### Port 8000 already in use
```bash
php artisan serve --port=8001
```

### Port 5173/5174 already in use
```bash
npm run dev -- --port 5175
```

### Database not found
```bash
php artisan migrate
```

### Assets not loading
```bash
npm run build
php artisan serve
```

### Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

---

## 📚 Documentation Files

- `DATABASE_CONNECTION_GUIDE.md` - Detailed database setup
- `PROJECT_STATUS.md` - Complete project overview
- `DATABASE_MIGRATION_COMPLETE.md` - Migration details
- `DEPLOYMENT_COMPLETE.md` - Full deployment guide
- `API_REFERENCE.md` - API endpoints
- `TESTING_GUIDE.md` - Testing setup

---

## ✅ Verification

Run this to verify everything:
```bash
# Check Laravel version
php artisan --version
# Output: Laravel Framework 12.47.0

# Check database
php artisan tinker
>>> DB::table('migrations')->count()
# Output: 8

# Check routes
php artisan route:list
```

---

## 🎯 Next Steps

1. ✅ Start both servers (Backend + Frontend)
2. ✅ Open http://localhost:8000
3. ✅ Test routes (/, /test, /login)
4. ✅ Create sample data with `php artisan tinker`
5. ✅ Begin development!

---

**Status:** 🟢 Production Ready  
**Last Updated:** Today  
**Ready for Development:** ✅ YES
