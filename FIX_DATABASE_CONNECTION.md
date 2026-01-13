# 🔧 Fix MySQL Connection Error - Railway

Database MySQL tidak terhubung dengan Laravel di Railway. Ikuti checklist ini untuk fix.

---

## ✅ Checklist: Database Connection Setup

### 1️⃣ **Railway MySQL Service Status**

Di Railway Dashboard:
1. Buka project `tiktok-analysis`
2. Lihat services di bawah:
   - [ ] Apakah ada service dengan nama **MySQL**?
   - [ ] Status-nya **"Deployed"** dengan checkmark hijau ✓?
   - [ ] Atau status "Deploying" (orange) atau "Failed" (red)?

**Jika TIDAK ada MySQL service:**
- Klik **"+ New"**
- Pilih **"MySQL"**
- Tunggu sampai "Deployed" ✓

**Jika status Failed/Error:**
- Delete service MySQL
- Create baru

---

### 2️⃣ **Verify MySQL Credentials**

Di Railway Dashboard:
1. Klik service **MySQL** (BUKAN web!)
2. Tab **"Variables"**
3. Catat/copy nilai ini:

```
MYSQL_HOST       = ? (biasanya: mysql.railway.internal)
MYSQL_PORT       = ? (biasanya: 3306)
MYSQL_USER       = ? (biasanya: root)
MYSQL_PASSWORD   = ? (string panjang random)
MYSQL_DATABASE   = ? (biasanya: railway)
```

⚠️ **PENTING:** Copy EXACT, jangan typo!

---

### 3️⃣ **Set Environment Variables di Web Service**

Di Railway Dashboard:
1. Klik service **web** (BUKAN MySQL!)
2. Tab **"Variables"**
3. **VERIFY/UPDATE** variables ini:

```
DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=PASTE_MYSQL_PASSWORD_HERE
APP_URL=https://tiktok-analysis-production.up.railway.app
APP_KEY=base64:RugGiCP+abQZ4U1hSSNnN2N3j4vT6nA4qLQis4VjpU0=
APP_ENV=production
APP_DEBUG=false
```

⚠️ **CRITICAL:**
- `DB_HOST` harus `mysql.railway.internal` (NOT localhost!)
- `DB_PASSWORD` harus EXACT dari MYSQL_PASSWORD
- `APP_URL` harus dengan `https://` di depan

---

### 4️⃣ **Check Connection**

Setelah variables di-set, Railway akan auto-restart. Tunggu ~1 menit.

Kemudian check logs:
```bash
railway logs -n 50
```

**Cari untuk:**
- ✅ "INFO Server running" - GOOD
- ❌ "SQLSTATE" - BAD (database error)
- ❌ "Access denied" - BAD (password salah)
- ❌ "Connection refused" - BAD (host salah)

---

## 🆘 **Troubleshooting Database Errors**

### ❌ Error: "SQLSTATE[HY000] [1045] Access denied"

**Cause:** Password salah atau username salah

**Fix:**
1. Buka Railway MySQL service
2. Tab Variables
3. Copy EXACT dari MYSQL_PASSWORD (termasuk special characters!)
4. Paste ke DB_PASSWORD di web service
5. Restart web service

### ❌ Error: "Access denied for user 'root'@'mysql.railway.internal'"

**Cause:** Username atau password salah

**Fix:**
1. Check MYSQL_USER (usually: `root`)
2. Update DB_USERNAME = MYSQL_USER
3. Update DB_PASSWORD = MYSQL_PASSWORD (EXACT!)
4. Restart

### ❌ Error: "Connection refused" atau "Can't connect to MySQL server"

**Cause:** Host atau port salah

**Fix:**
1. DB_HOST harus `mysql.railway.internal` (BUKAN localhost!)
2. DB_PORT harus `3306`
3. Verify MySQL service status = "Deployed" ✓

### ❌ Error: "No such file or directory" dalam migrations

**Cause:** Database belum di-create atau migrations belum run

**Fix:**
1. Ensure database connected (check di atas)
2. Run migrations via Railway Dashboard Execution
   - Command: `php artisan migrate --force`

---

## 📋 **Complete Setup Checklist**

- [ ] MySQL service created & status "Deployed" ✓
- [ ] MySQL Variables copied (MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, etc)
- [ ] Web service variables updated:
  - [ ] DB_CONNECTION=mysql
  - [ ] DB_HOST=mysql.railway.internal
  - [ ] DB_PORT=3306
  - [ ] DB_DATABASE=railway
  - [ ] DB_USERNAME=root
  - [ ] DB_PASSWORD=EXACT_PASSWORD
  - [ ] APP_URL=https://...
  - [ ] APP_KEY=base64:...
  - [ ] APP_ENV=production
  - [ ] APP_DEBUG=false
- [ ] Web service restarted (auto or manual)
- [ ] Logs check - no "Access denied" or "Connection refused"
- [ ] Migrations run: `php artisan migrate --force`
- [ ] Application accessible: https://tiktok-analysis-production.up.railway.app

---

## 🚀 **Next Steps**

1. **Verify semua environment variables** (Step 3 di atas)
2. **Check logs:** `railway logs -n 50`
3. **Jika masih error, screenshot dan share:**
   - Error message lengkap
   - MySQL service status
   - Web service variables (password bisa di-mask)

---

**Sudah di-check? Bagikan update! 🎯**
