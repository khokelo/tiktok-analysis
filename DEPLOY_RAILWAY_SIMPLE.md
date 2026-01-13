# 🚀 Deploy ke Railway - STEP BY STEP

Ikuti langkah ini untuk deploy aplikasi dengan sukses.

---

## 📝 STEP 1: Persiapan (5 menit)

### 1.1 Generate APP_KEY
```bash
php artisan key:generate --show
# Catat key yang muncul: base64:xxxx...
```

### 1.2 Test Aplikasi Lokal
```bash
php artisan migrate
php artisan serve
# Buka http://localhost:8000
# Pastikan tidak ada error
```

### 1.3 Commit ke GitHub
```bash
git add .
git commit -m "Ready for Railway deployment"
git push origin main
```

---

## 🎯 STEP 2: Setup Railway (10 menit)

### 2.1 Buat Railway Account
1. Buka https://railway.app
2. Klik **Sign Up**
3. Pilih **Sign up with GitHub**
4. Authorize & confirm email

### 2.2 Create Project
1. Di Dashboard, klik **New Project**
2. Pilih **Deploy from GitHub repo**
3. Authorize Railway (jika diminta)
4. **Cari & pilih**: khokelo/tiktok-analysis
5. Klik **Deploy Now**

**⏳ Railway akan start building - tunggu...**

---

## 🗄️ STEP 3: Setup Database (5 menit)

### Pilih salah satu:

#### **Option A: MySQL** (Recommended)
1. Di Railway dashboard, klik **+ New**
2. Pilih **MySQL**
3. Tunggu status menjadi **"Deployed"** ✓
4. Klik service MySQL → tab **Variables**
5. Copy/catat:
   - `MYSQL_HOST` (biasanya: mysql.railway.internal)
   - `MYSQL_PORT` (biasanya: 3306)
   - `MYSQL_ROOT_PASSWORD`
   - `MYSQL_DATABASE`

#### **Option B: PostgreSQL**
1. Klik **+ New**
2. Pilih **PostgreSQL**
3. Tunggu status **"Deployed"** ✓
4. Klik service → tab **Variables**
5. Copy `DATABASE_URL` (sudah lengkap!)

---

## 🔐 STEP 4: Set Environment Variables (5 menit)

### Di Railway Dashboard:
1. Klik project **tiktok-analysis**
2. Klik tab **Variables**
3. **Add** variabel berikut:

```
APP_NAME=TikTok Analysis
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:XXXX (dari Step 1.1)
APP_URL=https://YOUR_APP.up.railway.app

# Database - jika MySQL
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=XXXX (dari Step 3)

# Atau jika PostgreSQL - lebih simple!
DATABASE_URL=postgresql://user:pass@host:5432/db

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_LEVEL=warning
MAIL_MAILER=log
SANCTUM_STATEFUL_DOMAINS=your-domain.com
```

---

## 🚀 STEP 5: Deploy (1 menit)

### Trigger deployment by pushing code:

```bash
# Dari project directory
git add .
git commit -m "Deploy to Railway"
git push origin main
```

**Atau jika sudah push:**
- Railway otomatis akan trigger deployment ulang
- Buka Railway dashboard → Deployments tab
- Lihat log build progress

---

## ✅ STEP 6: Run Database Migrations (2 menit)

Setelah deployment berhasil (status **Deployed ✓**), jalankan migrations.

### Option A: Via Railway CLI (fastest)
```bash
# Install Railway CLI (jika belum)
npm install -g @railway/cli

# Login & link
railway login
railway link

# Run migrations
railway run php artisan migrate --force

# Seed admin user
railway run php artisan db:seed --class=AdminUserSeeder
```

### Option B: Via Railway Dashboard
1. Klik service **web**
2. Tab **Execution**
3. Klik **Run Command**
4. Input: `php artisan migrate --force`
5. Klik **Execute**
6. Repeat dengan: `php artisan db:seed --class=AdminUserSeeder`

---

## 🧪 STEP 7: Test & Verify (2 menit)

### Open aplikasi:
```
https://YOUR_APP.up.railway.app
```

### Test login:
```
Email: admin@example.com
Password: (dari seeding atau default)
```

### Check logs jika ada error:
```bash
railway logs --tail
```

---

## ✨ Selesai! 🎉

Aplikasi sudah live di Railway!

---

## 🆘 Troubleshooting

### Problem: Build Failed
```
Check: Railway Deployments → View Logs
Cause: Biasanya dependency atau syntax error
Fix: 
  - Fix error lokal: php artisan tinker
  - Commit & push ulang: git push origin main
```

### Problem: 500 Error saat akses
```
railway logs --tail
Kemungkinan:
1. APP_KEY tidak set
2. Database connection failed
3. Migration belum dijalankan

Fix:
  railway run php artisan cache:clear
  railway run php artisan config:cache
```

### Problem: Database Connection Refused
```
Check di Railway Dashboard:
1. Database service status → "Deployed" ✓?
2. Variables tab → credentials ada?
3. DB_HOST, DB_USERNAME, DB_PASSWORD benar?

Fix: Update env variables & restart
```

### Problem: Migrations fail
```
railway logs --tail
Kemungkinan:
1. Migration files error
2. Database not exist
3. Foreign key constraint

Fix:
  railway run php artisan migrate:reset
  railway run php artisan migrate --force
```

---

## 📊 Files & Konfigurasi

| File | Status | Keterangan |
|------|--------|-----------|
| Dockerfile | ✅ Ready | Container config |
| Procfile | ✅ Updated | Web + Release process |
| railway.json | ✅ Ready | Deployment config |
| composer.json | ✅ Ready | PHP dependencies |
| package.json | ✅ Ready | Node dependencies |
| .env.production | ✅ Template | Copy ke Railway Variables |

---

## 📞 Help

Jika masih error:
1. Check `railway logs --tail`
2. Check Railway dashboard logs
3. Verify env variables di Railway
4. Try restart: `railway restart`

Atau baca:
- TROUBLESHOOTING.md
- DEPLOYMENT_GUIDE.md

---

**Selamat! Aplikasi TikTok Analysis sudah deployed! 🚀**
