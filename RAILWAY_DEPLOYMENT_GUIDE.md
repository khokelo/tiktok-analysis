# 🚀 Panduan Lengkap Deploy ke Railway

Dokumentasi step-by-step untuk deploy aplikasi TikTok Analysis ke Railway dengan mudah.

## 📋 Prasyarat

- [GitHub Account](https://github.com) (sudah punya repository)
- [Railway Account](https://railway.app) (gratis dengan credit $5/bulan)
- Git terinstall di local
- Terminal/Command Prompt

---

## 🔧 Step 1: Setup Railway Account & Project

### 1.1 Buat Railway Account
1. Buka https://railway.app
2. Click "Login" → "Sign up with GitHub"
3. Authorize Railway untuk akses ke GitHub
4. Pilih workspace (atau buat baru)

### 1.2 Buat Project Baru
1. Di Railway Dashboard, klik **"New Project"**
2. Pilih **"Deploy from GitHub repo"**
3. Authorize Railway ke GitHub (jika belum)
4. Cari dan pilih repository: `khokelo/tiktok-analysis`
5. Click **"Deploy Now"**

---

## 🗄️ Step 2: Setup Database

Railway akan otomatis create project, tapi kita perlu setup database. Pilih salah satu:

### Option A: MySQL (Recommended)

1. Di Railway Dashboard project Anda, klik **"+ New"**
2. Pilih **"MySQL"**
3. Tunggu hingga service siap (status "Deployed")
4. Klik MySQL service → tab **"Variables"**
5. Copy nilai berikut:
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`
   - `MYSQL_DATABASE`

### Option B: PostgreSQL

1. Di Railway Dashboard project Anda, klik **"+ New"**
2. Pilih **"PostgreSQL"**
3. Tunggu hingga service siap
4. Railway akan otomatis create `DATABASE_URL` environment variable

---

## 🔐 Step 3: Setup Environment Variables

### 3.1 Di Railway Dashboard

1. Buka project Anda
2. Klik **"Variables"** tab
3. Tambahkan environment variables:

```
APP_NAME=TikTok Analysis
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE (lihat Step 3.2)
APP_URL=https://your-railway-url.up.railway.app

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=YOUR_PASSWORD

# Atau gunakan DATABASE_URL jika PostgreSQL
# DATABASE_URL=postgresql://user:password@host:port/database

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database
CACHE_PREFIX=tiktok_analysis_

# Queue
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@tiktokanalysis.com
MAIL_FROM_NAME=TikTok Analysis

# Security
SANCTUM_STATEFUL_DOMAINS=your-domain.com
```

### 3.2 Generate APP_KEY

Jika belum punya APP_KEY, generate di local:

```bash
php artisan key:generate
# Copy nilai APP_KEY dari .env
```

---

## 🚀 Step 4: Deploy Aplikasi

### 4.1 Push ke GitHub (trigger deployment)

```bash
# Dari root project directory
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

### 4.2 Monitor Deployment

1. Di Railway Dashboard, klik **"Deployments"** tab
2. Lihat status deployment (building, deploying, deployed)
3. Tunggu hingga status "Deployed" dengan checkmark ✓

### 4.3 Troubleshooting Deployment

Jika ada error, klik **"View Logs"**:

```
Error: npm: command not found
→ Solution: Pastikan Dockerfile include npm install

Error: composer: command not found
→ Solution: Pastikan COPY composer dan install di Dockerfile

Error: Database connection failed
→ Solution: Cek env variables di Railway dashboard
```

---

## ✅ Step 5: Run Database Migrations

Setelah deployment berhasil, jalankan migrations:

### Option 1: Menggunakan Railway CLI

```bash
# Install Railway CLI
npm install -g @railway/cli

# Login ke Railway
railway login

# Connect ke project
railway link

# Run migrations
railway run php artisan migrate --force

# Seed admin user (optional)
railway run php artisan db:seed --class=AdminUserSeeder
```

### Option 2: Menggunakan Railway Dashboard

1. Buka project di Railway
2. Klik service "web" (Laravel app)
3. Tab **"Execution"** → **"Run Command"**
4. Input: `php artisan migrate --force`
5. Click **"Execute"**
6. (Optional) Repeat dengan: `php artisan db:seed --class=AdminUserSeeder`

### Option 3: Menggunakan Procfile

Edit `Procfile` di root project:

```procfile
web: php artisan serve --host=0.0.0.0 --port=$PORT
release: php artisan migrate --force
```

Railway akan otomatis run migration saat deployment.

---

## 🌐 Step 6: Setup Custom Domain (Optional)

1. Di Railway Dashboard, klik service "web"
2. Tab **"Settings"** → **"Domains"**
3. Click **"+ Generate Domain"** atau **"+ Add Custom Domain"**
4. Jika custom domain:
   - Update DNS records (CNAME ke Railway domain)
   - Update `APP_URL` di env variables

---

## 🧪 Step 7: Testing & Verification

### 7.1 Test Aplikasi

```bash
# Test health check
curl https://your-app.up.railway.app/

# Login
https://your-app.up.railway.app/login

# Check storage
https://your-app.up.railway.app/uploads
```

### 7.2 Check Logs

```bash
# Dari local, dengan Railway CLI
railway logs

# Atau di Dashboard:
# Klik service → "Deployment" tab → klik deployment → view logs
```

### 7.3 Common Issues & Solutions

#### Issue: 500 Internal Server Error

```bash
# Check logs
railway logs

# Run artisan commands
railway run php artisan cache:clear
railway run php artisan config:cache
railway run php artisan view:cache
```

#### Issue: Database Connection Failed

```
Check:
1. APP_KEY di env variables
2. Database credentials (username, password, host)
3. Database sudah dibuat
4. Migration sudah dijalankan
```

#### Issue: File Upload Failed

```
Check:
1. FILESYSTEM_DISK=public di .env
2. storage/app/public sudah di-symlink
3. Write permissions di storage directory
```

---

## 📊 Monitoring & Maintenance

### 7.1 View Metrics

1. Di Railway Dashboard, tab **"Metrics"**
2. Monitor:
   - CPU usage
   - Memory usage
   - Network I/O
   - Deployment history

### 7.2 View Logs

```bash
# Real-time logs
railway logs --tail

# Log file terakhir 100 baris
railway logs -n 100
```

### 7.3 Manage Services

- **Storage**: Railway menyediakan disk otomatis (ephemeral)
- **Database Backup**: Setup manual atau gunakan Railway's backup feature
- **Scaling**: Upgrade resources di plan settings jika perlu

---

## 🔄 Deployment Updates

Setiap kali push ke GitHub:

```bash
git add .
git commit -m "Update message"
git push origin main
```

Railway akan otomatis:
1. Pull kode terbaru
2. Build Docker image
3. Deploy aplikasi
4. Restart services

---

## 🆘 Troubleshooting

### Problem: Build Fails

1. Check Dockerfile syntax
2. Ensure all dependencies di composer.json & package.json
3. Check for syntax errors di code

```bash
# Test locally
docker build -t tiktok-analysis .
```

### Problem: Runtime Crashes

1. Check logs: `railway logs`
2. Check env variables di Railway dashboard
3. Run migrations jika belum

### Problem: Database Issues

1. Verify database connection string
2. Check if database service is running
3. Run migrations manually

---

## 🎉 Success Checklist

- ✅ Railway Account created
- ✅ Project linked to GitHub
- ✅ Database service setup
- ✅ Environment variables configured
- ✅ APP_KEY generated & set
- ✅ Deployment successful
- ✅ Migrations run
- ✅ Login works
- ✅ Dashboard loads
- ✅ File upload works

---

## 📚 Useful Links

- [Railway Documentation](https://docs.railway.app)
- [Railway CLI Reference](https://docs.railway.app/develop/cli)
- [Laravel Deployment Guide](https://laravel.com/docs/12/deployment)
- [Docker Documentation](https://docs.docker.com)

---

## 💬 Need Help?

If you encounter issues:

1. Check this guide's Troubleshooting section
2. View Railway logs: `railway logs`
3. Check Laravel logs: `storage/logs/laravel.log`
4. Visit [Railway Support](https://railway.app/support)
5. Visit [Laravel Community](https://laracasts.com)

---

**Happy deploying! 🚀**
