# 🔧 MySQL Error Fix - Railway Deployment

Aplikasi crash karena MySQL error. Ikuti fix berikut untuk resolve masalahnya.

---

## ❌ Masalah yang terjadi:

1. **Database connection failed** - credentials tidak match
2. **Session/Cache table tidak ada** - belum run migration
3. **Default database setting salah** - defaultnya PostgreSQL, bukan MySQL

---

## ✅ Solusi (4 Langkah):

### Step 1: Verify Railway MySQL Service ✓

Di Railway Dashboard:
1. Buka project **tiktok-analysis**
2. Klik service **MySQL** (bukan web)
3. Tab **"Variables"** - copy credentials:

```
MYSQL_HOST          → mysql.railway.internal
MYSQL_PORT          → 3306
MYSQL_USER          → root (atau username yang di-set)
MYSQL_PASSWORD      → PASSWORD_STRING
MYSQL_DATABASE      → railway
```

⚠️ **PENTING**: Catat MYSQL_PASSWORD dengan tepat!

---

### Step 2: Set Correct Environment Variables

Di Railway Dashboard:
1. Klik service **web** (bukan MySQL)
2. Tab **"Variables"** - UPDATE dengan nilai dari Step 1:

```
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=XXXX_DARI_MYSQL_SERVICE_XXXX
```

⚠️ **PASSWORD HARUS TEPAT!** Copy dari MySQL service variables.

---

### Step 3: Push Code Fix ke GitHub

Ini adalah fix untuk config database.php:

```bash
git add .
git commit -m "Fix: Use MySQL as default database for production"
git push origin main
```

Railway akan **otomatis redeploy** dengan config baru.

**Tunggu deployment selesai** (check Deployments tab).

---

### Step 4: Run Database Migrations

Setelah deployment berhasil (status **Deployed ✓**):

```bash
# Option A: Via Railway CLI (fastest)
npm install -g @railway/cli
railway login
railway link

# Run migrations
railway run php artisan migrate --force
railway run php artisan db:seed --class=AdminUserSeeder
```

**Atau Option B: Via Railway Dashboard**
1. Klik service **web**
2. Tab **"Execution"**
3. Klik **"Run Command"**
4. Input: `php artisan migrate --force`
5. Klik **"Execute"**
6. Tunggu selesai (lihat output)

---

## 🧪 Verifikasi Fix

### Check 1: Database Connected
```bash
railway logs --tail
# Cari: "Connection successful" atau "Migrations completed"
# Jangan ada: "SQLSTATE", "Access denied", "Connection refused"
```

### Check 2: Open Aplikasi
```
https://your-app.up.railway.app
```

Jika masih error 500:
```bash
railway run php artisan cache:clear
railway run php artisan config:cache
railway restart
```

### Check 3: Verify Tables
```bash
railway run php artisan tinker
>>> DB::table('users')->count()
# Harus return: 1 (admin user)
```

---

## 🆘 Jika Masih Error

### Error: "Access denied for user 'root'"
```
Cause: PASSWORD salah atau tidak di-set
Fix: 
1. Copy MYSQL_PASSWORD dari MySQL service (exact!)
2. Update DB_PASSWORD di web service variables
3. Restart web service
4. Clear cache: railway run php artisan cache:clear
```

### Error: "SQLSTATE[42000]" atau "Unknown database"
```
Cause: DB_DATABASE salah
Fix:
1. Check MYSQL_DATABASE dari MySQL service
2. Update DB_DATABASE di web service
3. Restart
```

### Error: "Connection refused" atau "Unknown host"
```
Cause: DB_HOST atau DB_PORT salah
Fix:
1. DB_HOST harus: mysql.railway.internal (bukan localhost!)
2. DB_PORT harus: 3306
3. Update di web service variables
4. Restart
```

### Error: "No such table: sessions"
```
Cause: Migrations belum dijalankan
Fix:
railway run php artisan migrate --force
```

---

## 📝 Checklist

- [ ] MySQL service di Railway sudah "Deployed" ✓
- [ ] Credentials di web service variables benar (password exact!)
- [ ] DB_CONNECTION=mysql
- [ ] DB_HOST=mysql.railway.internal
- [ ] Code push ke GitHub (deployment triggered)
- [ ] Deployment selesai (status Deployed ✓)
- [ ] Migrations dijalankan
- [ ] Cache cleared
- [ ] Aplikasi accessible tanpa error

---

## 📊 Quick Reference

| Error | Cause | Fix |
|-------|-------|-----|
| Access denied | Password salah | Copy dari MySQL service |
| Connection refused | Host salah | Use `mysql.railway.internal` |
| Unknown database | DB_DATABASE salah | Match dengan MYSQL_DATABASE |
| No table: sessions | Migrations belum run | `railway run php artisan migrate --force` |
| 500 error | Cache issue | `railway run php artisan cache:clear` |

---

## 🎯 Next Steps

1. **Verify MySQL credentials** (Step 1-2)
2. **Push code** (Step 3)
3. **Monitor deployment** (refresh Deployments tab)
4. **Run migrations** (Step 4)
5. **Test aplikasi** (Step 5)

Setelah selesai, aplikasi harus berjalan dengan baik! ✅

---

**Butuh bantuan? Share error message lengkap di logs!**
```bash
railway logs --tail
```
