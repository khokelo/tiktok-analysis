# 🔍 MySQL Database Troubleshooting - Railway

Database tidak terkoneksi atau tables tidak ada di Railway MySQL. Ikuti guide ini untuk diagnose dan fix.

---

## 🚀 Quick Diagnostic (5 min)

### Via Railway Dashboard Execution:

1. **Railway Dashboard** → Project `tiktok-analysis`
2. Service **web** → Tab **Execution**
3. **Run Command:**
   ```
   php artisan db:diagnose
   ```

**Output akan show:**
- ✅ Atau ❌ untuk setiap aspek
- Tables yang ada dan yang missing
- Rekomendasi action

---

## 🔧 Step-by-Step Fix

### Step 1: Verify MySQL Service Status

**Railway Dashboard:**
1. Project `tiktok-analysis`
2. **Service MySQL**
3. Status harus **"Deployed"** ✓ (hijau checkmark)

**Jika FAILED/RED:**
- Delete service
- Create baru: **+ New** → **MySQL**
- Tunggu "Deployed" ✓

---

### Step 2: Verify Database Credentials

**Buka Railway MySQL Service:**
1. Click service **MySQL**
2. Tab **Variables**
3. **CATAT/COPY** nilai ini EXACT:

```
MYSQL_HOST           = mysql.railway.internal
MYSQL_PORT           = 3306
MYSQL_ROOT_PASSWORD  = [panjang string random]
MYSQL_DATABASE       = railway
MYSQL_USER           = (jika ada, biasanya root)
```

⚠️ **CRITICAL:** `MYSQL_ROOT_PASSWORD` harus di-copy PERSIS (jangan typo!)

---

### Step 3: Update Web Service Variables

**Buka Railway Web Service:**
1. Click service **web**
2. Tab **Variables**
3. **VERIFY/UPDATE** (match dengan MYSQL values):

```
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=PASTE_MYSQL_ROOT_PASSWORD_HERE (EXACT!)
```

**SAVE** - Railway auto-restart

---

### Step 4: Run Migrations

**Via Execution Tab:**
1. Service **web** → Tab **Execution**
2. **Run Command #1:**
   ```
   php artisan migrate --force
   ```
   - Tunggu output
   - Harus show: "✓ Migrations completed" atau "Migrated..."

3. **Run Command #2:** (jika #1 success)
   ```
   php artisan db:seed --class=AdminUserSeeder
   ```
   - Tunggu selesai

---

### Step 5: Verify with Diagnostics

**Run diagnose command:**
1. Service **web** → Execution
2. **Command:**
   ```
   php artisan db:diagnose
   ```

**Output harus show:**
```
✅ Connection successful!
✅ Migrations table exists
Migrated files: 8
✅ Found 8 tables:
  ✅ 👤 Users
  ✅ 🔐 Sessions
  ✅ 💾 Cache
  ✅ ⚙️  Jobs
  ✅ 📝 Migrations
  ✅ 💰 Sales
  ✅ 🎵 TikTok Sales
  ✅ 📁 Uploaded Files
```

---

## 🆘 Troubleshooting Specific Errors

### ❌ "Connection refused" atau "Cannot connect to MySQL"

**Cause:** `DB_HOST` salah atau MySQL service tidak running

**Fix:**
1. Ensure DB_HOST = `mysql.railway.internal` (EXACT!)
2. Ensure MySQL service status = "Deployed" ✓
3. Restart web service (or redeploy)

### ❌ "Access denied for user 'root'"

**Cause:** `DB_PASSWORD` salah

**Fix:**
1. **COPY EXACT** dari `MYSQL_ROOT_PASSWORD` di MySQL service Variables
2. **PASTE EXACT** ke `DB_PASSWORD` di web service Variables
3. **NO TYPOS!** Including special characters like `@`, `$`, etc.
4. Restart/redeploy

### ❌ "Unknown database 'railway'"

**Cause:** Database name salah atau MySQL belum initialize

**Fix:**
1. Verify `MYSQL_DATABASE=railway` di MySQL service
2. Verify `DB_DATABASE=railway` di web service (EXACT match!)
3. Delete MySQL service, create baru
4. Ensure new MySQL service fully "Deployed" before using

### ❌ "No such table: migrations" saat migrate

**Cause:** Database OK tapi migrations belum run sama sekali

**Fix:**
Run migrations:
```
php artisan migrate --force
```

### ❌ "SQLSTATE[42S02]: Table not found"

**Cause:** Migrations incomplete

**Fix:**
1. Run: `php artisan migrate:reset` (jika ada data lama)
2. Run: `php artisan migrate --force`
3. Run: `php artisan db:seed --class=AdminUserSeeder`

---

## 📋 Complete Checklist

- [ ] MySQL service **Deployed** ✓ (status green)
- [ ] MYSQL_ROOT_PASSWORD di-copy EXACT
- [ ] DB_PASSWORD di web service = MYSQL_ROOT_PASSWORD (EXACT!)
- [ ] DB_HOST = `mysql.railway.internal`
- [ ] DB_DATABASE = `railway`
- [ ] DB_USERNAME = `root`
- [ ] Web service restart/redeploy
- [ ] Migrations run successfully
- [ ] `php artisan db:diagnose` show all ✅
- [ ] Application accessible: https://tiktok-analysis-production.up.railway.app
- [ ] Login page loads without 500 error

---

## 🎯 If Still Not Working

1. **Screenshot & share:**
   - MySQL service Variables
   - Web service Variables (DB_*)
   - Output dari `php artisan db:diagnose`
   - Full error message dari logs

2. **Run this to get detailed logs:**
   ```
   railway logs -n 200
   ```
   Share output lengkap

---

## ✅ Success Indicators

- ✅ `php artisan db:diagnose` menunjukkan semua tables exist
- ✅ Application homepage loads tanpa 500 error
- ✅ `/login` page accessible
- ✅ No database errors di logs

---

**Sudah follow steps ini?** Beri tahu hasilnya! 🚀
