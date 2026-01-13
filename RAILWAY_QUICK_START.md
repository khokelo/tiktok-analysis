# 🚀 Railway Deployment - Quick Start (5 Menit)

Panduan cepat deploy ke Railway tanpa ribet!

---

## 📋 Prerequisites

- ✅ GitHub Account (sudah punya repo)
- ✅ Railway Account (daftar gratis: https://railway.app)
- ✅ Aplikasi tested di local (berjalan dengan baik)

---

## ⚡ Quick Deploy Steps

### 1️⃣ Login Railway & Create Project (2 min)

```bash
# Buka: https://railway.app
# Click "New Project"
# Select "Deploy from GitHub"
# Authorize & Select repo: khokelo/tiktok-analysis
# Click "Deploy"
```

**Railway akan:**
- ✅ Build Docker image
- ✅ Deploy web service
- ✅ Assign domain otomatis

---

### 2️⃣ Create Database (1 min)

Di Railway Dashboard:
1. Click **"+ New"** service
2. Pilih **"MySQL"** atau **"PostgreSQL"**
3. Tunggu "Deployed" status
4. Klik database service → **"Variables"** tab
5. Copy credentials

---

### 3️⃣ Set Environment Variables (1 min)

Di Railway Dashboard project Anda:

Click **"Variables"** tab → Add these:

```
APP_NAME=TikTok Analysis
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=YOUR_PASSWORD_HERE

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@tiktokanalysis.com
SANCTUM_STATEFUL_DOMAINS=your-domain.com
```

⚠️ **Replace:**
- `YOUR_KEY_HERE` → Buka `.env.local`, copy value APP_KEY
- `YOUR_PASSWORD_HERE` → Dari MySQL credentials di Railway

---

### 4️⃣ Commit & Push ke GitHub (1 min)

```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

✅ **Railway akan otomatis trigger deployment**

---

### 5️⃣ Run Migrations (Optional, 1 min)

Setelah deployment berhasil, jalankan migrations:

**Opsi A: Via Railway CLI (fastest)**
```bash
# Install CLI
npm install -g @railway/cli

# Login & link
railway login
railway link

# Run migrations
railway run php artisan migrate --force
railway run php artisan db:seed --class=AdminUserSeeder
```

**Opsi B: Via Railway Dashboard**
1. Click service "web"
2. Tab **"Execution"**
3. Click **"Run Command"**
4. Input: `php artisan migrate --force`
5. Click **"Execute"**

**Opsi C: Via Procfile (auto)**
- Already configured ✅ (migrations run automatically)

---

## ✅ Verification

```bash
# Open aplikasi
https://your-app.up.railway.app

# Test login
Username: admin
Password: (dari seed atau admin credentials)

# Check status
# Dashboard → Service "web" → Metrics
```

---

## 🎉 Done! 

Aplikasi sudah live! 🚀

---

## 🆘 Troubleshooting

### ❌ Aplikasi crash/error 500

```bash
# Check logs
railway logs --tail

# Or di Railway Dashboard:
# Service → Deployments → View Logs
```

### ❌ Database connection failed

**Verify:**
1. DB credentials di Railway Variables (copy lagi dari MySQL service)
2. DATABASE_URL format correct (jika PostgreSQL)
3. Database service sudah "Deployed" status

**Fix:**
```bash
railway run php artisan config:cache
railway restart
```

### ❌ Assets/CSS not loaded

```bash
# Rebuild assets
railway run npm run build

# Or redeploy
git commit --allow-empty -m "Rebuild assets"
git push
```

### ❌ Timeout during build

- Check Docker image size
- Remove unnecessary dependencies
- Clear npm cache: `npm cache clean --force`

---

## 📚 More Help

- 📖 Full guide: `RAILWAY_DEPLOYMENT_GUIDE.md`
- ✅ Checklist: `RAILWAY_CHECKLIST.md`
- 🔧 Troubleshooting: `TROUBLESHOOTING.md`

---

**Selamat! Aplikasi sudah deployed ke Railway! 🎉**
