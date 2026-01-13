# ⚡ Quick Start: Deploy to Railway dalam 10 Menit

Panduan cepat untuk deploy aplikasi ke Railway dengan GitHub Actions.

## 📋 Persiapan (5 menit)

### 1. Siapkan Local Project

```bash
# Navigate to project
cd c:\Users\Organizer1\OneDrive\Desktop\Projek\Laravel\ Project\tiktok-analysis

# Install dependencies
composer install
npm install --legacy-peer-deps

# Setup .env
cp .env.example .env
php artisan key:generate

# Test lokal
php artisan migrate
php artisan serve
```

### 2. Get APP_KEY untuk GitHub Secret

```bash
# Terminal
php artisan key:generate --show

# Output: base64:Xxxxx...
# Copy this value untuk GitHub Secrets nanti
```

---

## 🐙 Setup GitHub (2 menit)

### 1. Push Code ke GitHub

```bash
# Initialize git (jika belum)
git init
git add .
git commit -m "Initial commit"

# Add GitHub remote (ganti USERNAME)
git remote add origin https://github.com/USERNAME/tiktok-analysis.git
git branch -M main
git push -u origin main
```

### 2. Add GitHub Secrets

1. Buka: **Repository → Settings → Secrets and variables → Actions**
2. Click **New repository secret** untuk masing-masing:

```
Name: RAILWAY_TOKEN
Value: [Railway token dari https://railway.app → Account Settings → Tokens]

Name: APP_KEY  
Value: base64:Xxxxx... (dari output php artisan key:generate --show)

Name: RAILWAY_APP_URL
Value: (kosong dulu, update setelah first deploy)
```

---

## 🚂 Setup Railway (2 menit)

### 1. Create Railway Project

1. Buka https://railway.app
2. Click **+ New Project**
3. Select **GitHub Repo**
4. Search dan select `tiktok-analysis`
5. Click **Configure**

### 2. Add Database

Di Railway Dashboard:
1. Click **+ Create** → **MySQL**
2. Railway auto-connect database
3. Copy credentials

### 3. Set Environment Variables

Di Railway → Variables tab, add:

```env
APP_NAME=TikTok_Analysis
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:Xxxxx...
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=[from MySQL service]

CACHE_DRIVER=redis
SESSION_DRIVER=cookie
```

---

## 🚀 Deploy (1 menit)

### Otomatis Deploy

```bash
# Pastikan semua committed
git status

# Push ke main
git push origin main

# GitHub Actions otomatis:
# 1. Run tests
# 2. Build assets
# 3. Deploy ke Railway
```

### Monitor Deployment

1. **GitHub**: Repository → Actions → Monitor "Deploy to Railway" workflow
2. **Railway**: Deployments tab → View logs

---

## ✅ Verifikasi (1 menit)

```bash
# Test health endpoint
curl https://your-railway-app-url/health

# Test login
# Buka https://your-railway-app-url
# Login dengan admin account dari AdminUserSeeder
```

---

## 🎯 Next Steps

- Update `RAILWAY_APP_URL` secret dengan actual URL dari Railway
- Seed production database jika diperlukan
- Monitor logs di Railway dashboard
- Set up email notifications (optional)

---

**Done!** Aplikasi sudah live di production dengan CI/CD otomatis!

Setiap `git push` ke `main` akan otomatis test dan deploy.
