# 📚 Panduan Lengkap Deployment ke Railway dengan CI/CD

> Dokumentasi matang untuk deployment Laravel TikTok Analysis ke Railway dengan GitHub Actions CI/CD otomatis.

## 📋 Daftar Isi

1. [Prasyarat](#prasyarat)
2. [Setup Awal](#setup-awal)
3. [Konfigurasi GitHub](#konfigurasi-github)
4. [Konfigurasi Railway](#konfigurasi-railway)
5. [Deployment](#deployment)
6. [Monitoring & Troubleshooting](#monitoring--troubleshooting)
7. [Maintenance](#maintenance)

---

## ✅ Prasyarat

### Software yang Diperlukan
- **Git** (v2.30+) - Version control
- **Node.js** (v18+) - Asset compilation
- **PHP** (8.2+) - Laravel runtime
- **Composer** - PHP dependency manager
- **MySQL/PostgreSQL** - Database (opsional lokal, akan menggunakan Railway)

### Akun Online
- **GitHub Account** - Untuk repository dan CI/CD
- **Railway Account** - Platform deployment (gratis dengan credit)

### Verifikasi Instalasi
```bash
# Check versions
node --version        # v18+ required
npm --version         # 9+ required
php --version         # 8.2+ required
composer --version    # 2.0+ required
git --version         # 2.30+ required
```

---

## 🚀 Setup Awal

### 1. Persiapan Lokal

```bash
# Clone repository (atau gunakan yang sudah ada)
cd /path/to/project

# Install dependencies
composer install
npm install --legacy-peer-deps

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Buat database lokal (SQLite atau MySQL)
# Untuk SQLite:
touch database/database.sqlite

# Setup database (.env)
# DB_CONNECTION=sqlite
# DB_DATABASE=database/database.sqlite
```

### 2. Setup Database Lokal

```bash
# Run migrations
php artisan migrate

# Seed dengan admin user
php artisan db:seed --class=AdminUserSeeder

# Verify setup
php artisan tinker
>>> App\Models\User::count()
```

### 3. Build Assets & Test Lokal

```bash
# Build frontend assets
npm run build

# Start Laravel development server
php artisan serve

# Akses: http://localhost:8000
# Admin: Login dengan credential dari AdminUserSeeder
```

### 4. Git Configuration

```bash
# Initialize git (jika belum)
git init
git add .
git commit -m "Initial commit: TikTok Analysis with Charts"

# Add remote (jika sudah ada repo di GitHub)
git remote add origin https://github.com/USERNAME/REPO.git

# Create main branch (pastikan ada main branch, bukan master)
git checkout -b main
git push -u origin main
```

---

## 🐙 Konfigurasi GitHub

### 1. Create Repository

1. Buka https://github.com/new
2. **Repository name**: `tiktok-analysis`
3. **Description**: `TikTok Sales Analytics Dashboard with Advanced Charts`
4. **Visibility**: Public atau Private (sesuai preference)
5. **Initialize**: Jangan initialize README (karena sudah ada lokal)
6. Click **Create repository**

### 2. Push Code ke GitHub

```bash
# Add remote (ganti USERNAME dan REPO)
git remote add origin https://github.com/USERNAME/tiktok-analysis.git
git branch -M main
git push -u origin main

# Verify
git remote -v
# origin  https://github.com/USERNAME/tiktok-analysis.git (fetch)
# origin  https://github.com/USERNAME/tiktok-analysis.git (push)
```

### 3. Setup GitHub Secrets

Secrets ini digunakan oleh GitHub Actions untuk deploy ke Railway.

**Langkah:**
1. Buka Repository → Settings → Secrets and variables → Actions
2. Click **New repository secret**
3. Tambahkan secrets berikut:

#### Secret 1: RAILWAY_TOKEN
- **Name**: `RAILWAY_TOKEN`
- **Value**: Token dari Railway dashboard
  - Buka https://railway.app
  - Account Settings → Tokens
  - Generate new token
  - Copy dan paste di sini

#### Secret 2: RAILWAY_APP_URL
- **Name**: `RAILWAY_APP_URL`
- **Value**: URL aplikasi di Railway (akan tersedia setelah first deploy)
- Bisa update nanti setelah deployment

#### Secret 3: APP_KEY
- **Name**: `APP_KEY`
- **Value**: Hasil dari `php artisan key:generate --show` (lokal)
- Format: `base64:xxxxx...`

```bash
# Generate APP_KEY (jika belum ada)
php artisan key:generate --show
```

---

## 🚂 Konfigurasi Railway

### 1. Create Railway Account & Project

1. Buka https://railway.app
2. Sign up dengan GitHub (recommended) atau email
3. Click **Start a New Project**
4. Select **GitHub Repo** (connect your repo)
5. Select repository: `tiktok-analysis`
6. **Confirm** deployment preferences

### 2. Configure Environment Variables

Di Railway dashboard, buka project Anda:

1. **Click** "Variables" atau "Environment"
2. **Add the following variables:**

```env
# Application
APP_NAME=TikTok_Analysis
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app  # Replace dengan actual URL nanti
APP_KEY=base64:xxxxx...  # Gunakan value dari GitHub Secrets

# Database
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal  # Railway MySQL hostname
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=xxxxx  # Password yang di-generate Railway

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=cookie

# Mail (optional)
MAIL_MAILER=log  # Atau SMTP jika sudah siap

# Monitoring
LOG_CHANNEL=stack
```

### 3. Create MySQL Service (Optional but Recommended)

Jika menggunakan Railway MySQL:

1. Di Railway Dashboard, click **+ Create**
2. Select **MySQL**
3. Railway akan auto-connect database
4. Copy credentials ke environment variables

Atau gunakan existing database dengan connection string.

---

## 📤 Deployment

### 1. First Deployment (Manual)

```bash
# Ensure everything is committed
git status

# Verify pre-deployment checks
bash scripts/precheck.sh

# Push ke main branch
git push origin main

# Monitor GitHub Actions
# 1. Buka Repository → Actions
# 2. Lihat workflow "Deploy to Railway" sedang berjalan
# 3. Wait untuk "Test" job completed
# 4. Deploy job akan otomatis run
```

### 2. Monitor Deployment

```bash
# Di GitHub
Repository → Actions → Deploy to Railway workflow
- Check "Test" job logs untuk ensure tests passing
- Check "Deploy" job untuk deployment status
- Railway akan receive deployment request

# Di Railway
https://railway.app → Click project → Deployments
- Check latest deployment status
- View build logs
- View runtime logs
```

### 3. Verify Deployment

Setelah deployment successful:

```bash
# Test health endpoint
curl https://your-railway-app-url/health

# Check logs (di Railway)
Dashboard → Logs → View application logs

# Test authentication
curl -X POST https://your-railway-app-url/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

### 4. Post-Deployment Tasks

```bash
# 1. SSH ke Railway environment (jika diperlukan)
# Railway → Run command
php artisan cache:clear
php artisan config:cache

# 2. Seed production database (if needed)
php artisan db:seed --class=AdminUserSeeder

# 3. Check database migrations
php artisan migrate:status

# 4. Test critical features di production
# - Login dengan admin account
# - Upload CSV test file
# - Check dashboard charts
# - Verify dark mode toggle
```

---

## 🔍 Monitoring & Troubleshooting

### Health Check Endpoint

Endpoint `/health` digunakan Railway untuk monitoring:

```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected'
    ]);
});
```

### Common Issues & Solutions

#### ❌ Issue: "Database connection failed"
**Solution:**
```bash
# 1. Verify DB credentials di Railway environment
# 2. Check database service running
# 3. Run migration:
#    Railway → Run command → php artisan migrate --force
# 4. Check connection string format
```

#### ❌ Issue: "Cache driver error"
**Solution:**
```bash
# 1. Clear cache di deployment
#    php artisan cache:clear
# 2. Update CACHE_DRIVER ke redis atau file
# 3. Restart deployment
```

#### ❌ Issue: "Asset files not loading (404)"
**Solution:**
```bash
# 1. Verify npm run build successful di GitHub Actions
# 2. Check public/build directory exists
# 3. Verify APP_URL di .env
# 4. Rebuild assets:
#    npm run build
#    git commit -am "Rebuild assets"
#    git push origin main
```

#### ❌ Issue: "Migration failed"
**Solution:**
```bash
# 1. Check migration logs di Railway
# 2. Verify database user permissions
# 3. Test migration lokal dulu:
#    php artisan migrate --seed
# 4. Rollback jika perlu:
#    php artisan migrate:rollback
#    git revert [commit-hash]
#    git push origin main
```

#### ❌ Issue: "GitHub Actions test failing"
**Solution:**
```bash
# 1. Check GitHub Actions logs di Actions tab
# 2. Run tests lokal:
#    php artisan test
# 3. Fix failing tests
# 4. Commit dan push:
#    git commit -am "Fix tests"
#    git push origin main
```

### View Logs

**GitHub Actions Logs:**
- Repository → Actions → Select workflow → Select run

**Railway Logs:**
- Railway Dashboard → Project → View Logs
- Filter by date/time untuk troubleshooting

**PHP Error Logs:**
```bash
# SSH ke Railway
php artisan logs
```

---

## 🔧 Maintenance

### Regular Maintenance Tasks

#### Daily
- Monitor dashboard performance
- Check error logs
- Verify health endpoint responding

#### Weekly
- Review GitHub Actions history untuk failed deploys
- Check database size
- Monitor storage usage

#### Monthly
- Update dependencies:
  ```bash
  composer update
  npm update
  php artisan package:discover
  ```
- Review failed migrations
- Cleanup old logs

### Updating Application

```bash
# 1. Update code lokal
git pull origin main

# 2. Update dependencies
composer update
npm update

# 3. Test lokal
php artisan test

# 4. Build assets
npm run build

# 5. Commit dan push
git add .
git commit -m "Update dependencies and features"
git push origin main

# 6. GitHub Actions akan otomatis deploy
```

### Database Backup

```bash
# Export database dari Railway
# Option 1: MySQL command
mysqldump -h DB_HOST -u DB_USER -p DB_NAME > backup.sql

# Option 2: PHP Artisan (jika supported)
php artisan backup:run

# Option 3: Railway UI
Dashboard → Data → Backup/Export
```

### Rollback Deployment

Jika deployment error:

```bash
# Option 1: Revert last commit
git revert HEAD
git push origin main

# Option 2: Revert ke specific commit
git log --oneline  # Find commit hash
git revert [commit-hash]
git push origin main

# Option 3: Force deploy previous version (Railway UI)
Deployments → Select previous successful deployment → Redeploy
```

---

## 📊 Environment Setup Checklist

Sebelum production deployment, pastikan semua selesai:

```
PRE-DEPLOYMENT CHECKLIST
=======================

Local Setup:
☐ PHP 8.2+ installed
☐ Node.js 18+ installed
☐ Composer installed
☐ Git configured (git config --global)

Code Setup:
☐ Repository created di GitHub
☐ Code pushed to main branch
☐ .env.example updated dengan correct defaults
☐ database/database.sqlite exists (local testing)
☐ All migrations run successfully locally
☐ Tests passing locally (php artisan test)

GitHub Setup:
☐ Repository linked
☐ Branch protection: main branch (recommended)
☐ GitHub Secrets added:
  ☐ RAILWAY_TOKEN
  ☐ RAILWAY_APP_URL (can be updated later)
  ☐ APP_KEY

Railway Setup:
☐ Railway account created
☐ Project created
☐ Database service connected
☐ Environment variables configured
☐ Database credentials verified
☐ Health check endpoint configured

Pre-Deployment:
☐ bash scripts/precheck.sh passed
☐ npm run build successful
☐ php artisan migrate --seed successful
☐ All admin accounts created
☐ Email notifications configured (optional)
☐ Dark mode tested
☐ Charts rendering correctly

First Deployment:
☐ Code committed and pushed
☐ GitHub Actions running
☐ Tests passed
☐ Deployment completed
☐ Health endpoint responding
☐ Admin login working
☐ Dashboard accessible
☐ CSV upload tested
```

---

## 🔗 Useful Links

- **Railway Dashboard**: https://railway.app
- **GitHub Repository**: https://github.com/USERNAME/tiktok-analysis
- **Railway Docs**: https://docs.railway.app
- **Laravel Deployment**: https://laravel.com/docs/deployment
- **GitHub Actions**: https://docs.github.com/en/actions

---

## 📞 Support & Questions

Jika mengalami issue:

1. **Check troubleshooting section** di atas
2. **Review logs** di GitHub Actions dan Railway
3. **Test lokal dulu** sebelum push ke production
4. **Git revert** jika deployment error (aman)

---

**Last Updated**: 2024
**Version**: 1.0
**Status**: Production Ready
