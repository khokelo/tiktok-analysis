# ✅ Railway Deployment Checklist

Gunakan checklist ini untuk memastikan semua setup dengan benar sebelum dan sesudah deployment.

## 📋 Pre-Deployment Checklist

### Code & Repository
- [ ] Semua file sudah di-commit: `git status` menunjukkan "working tree clean"
- [ ] Latest version sudah di-push ke GitHub: `git log --oneline -5`
- [ ] Tidak ada uncommitted changes yang penting
- [ ] `.gitignore` sudah benar (jangan include .env, storage/logs, dll)

### Configuration Files
- [ ] `composer.json` sudah updated dengan semua dependencies
- [ ] `package.json` sudah updated dengan semua devDependencies
- [ ] `.env.production` dibuat dengan konfigurasi production
- [ ] `Dockerfile` valid dan tested
- [ ] `Procfile` dengan release & web process
- [ ] `railway.json` dengan konfigurasi deployment
- [ ] `app.php` (config) sudah verified

### Local Testing
- [ ] Aplikasi berjalan di local: `php artisan serve`
- [ ] Database migrations berjalan: `php artisan migrate`
- [ ] Asset build berhasil: `npm run build`
- [ ] Tidak ada errors di: `php artisan tinker`
- [ ] Test auth: login berhasil

### Database Preparation
- [ ] Database schema sudah finalized (tidak ada pending migrations)
- [ ] Seeders sudah siap (AdminUserSeeder, dll)
- [ ] No foreign key constraint issues
- [ ] Data migration scripts ready (jika ada dari prod lama)

---

## 🚀 Deployment Checklist

### Railway Setup
- [ ] Railway Account dibuat dan verified
- [ ] Project dibuat di Railway (connected to GitHub)
- [ ] Database service dibuat (MySQL atau PostgreSQL)
  - [ ] MySQL: Credentials tercopy dengan benar
  - [ ] PostgreSQL: DATABASE_URL tersedia
- [ ] Web service dibuat dan configured

### Environment Variables
- [ ] `APP_NAME` = "TikTok Analysis"
- [ ] `APP_ENV` = "production"
- [ ] `APP_DEBUG` = "false"
- [ ] `APP_URL` = Sesuai domain Railway atau custom domain
- [ ] `APP_KEY` = Generated dan di-set (bukan base64:something generic)
- [ ] `DB_CONNECTION` = "mysql" atau "pgsql" (sesuai pilihan)
- [ ] `DB_HOST` = Sesuai Railway database host
- [ ] `DB_PORT` = 3306 (MySQL) atau 5432 (PostgreSQL)
- [ ] `DB_DATABASE` = Nama database di Railway
- [ ] `DB_USERNAME` = Username database
- [ ] `DB_PASSWORD` = Password database (dari Railway secrets)
- [ ] `SESSION_DRIVER` = "database"
- [ ] `CACHE_STORE` = "database"
- [ ] `QUEUE_CONNECTION` = "database"
- [ ] `LOG_LEVEL` = "warning" (production)
- [ ] `MAIL_MAILER` = "log" (atau email service config)
- [ ] `SANCTUM_STATEFUL_DOMAINS` = Custom domain jika ada

### Code Push
- [ ] `git add .`
- [ ] `git commit -m "Prepare for Railway deployment"`
- [ ] `git push origin main`
- [ ] GitHub repo updated dengan latest changes

### Deployment Monitoring
- [ ] Railway deployment started (check Deployments tab)
- [ ] Build process berhasil (no Docker build errors)
- [ ] Assets compiled: `npm run build` successful
- [ ] Composer install berhasil
- [ ] Web service deployment berhasil (Status: Deployed ✓)

---

## ✅ Post-Deployment Checklist

### Run Migrations & Seeds
- [ ] Database migrations dijalankan: `railway run php artisan migrate --force`
  - Option 1: Via Railway Dashboard Execute Command
  - Option 2: Via Railway CLI
  - Option 3: Via Procfile release command (otomatis)
- [ ] Admin user seeded: `railway run php artisan db:seed --class=AdminUserSeeder`
- [ ] Tables created successfully di database

### Verify Application
- [ ] Aplikasi accessible di domain: OPEN https://your-app.up.railway.app
- [ ] Homepage loads tanpa error
- [ ] Login page renders correctly
- [ ] Login functionality works dengan admin account
- [ ] Dashboard loads setelah login
- [ ] Database connection working (no "Connection refused" errors)

### Clear Caches
- [ ] `railway run php artisan cache:clear`
- [ ] `railway run php artisan config:cache`
- [ ] `railway run php artisan view:cache` (optional)
- [ ] Restart web service jika perlu

### Check Logs
- [ ] Railway logs: `railway logs` - no critical errors
- [ ] Laravel logs: Check untuk warnings/errors
- [ ] Database logs: No connection errors

### Test Features
- [ ] Login works
- [ ] Dashboard displays correctly
- [ ] Profile page loads
- [ ] Sales data displays (jika ada)
- [ ] File upload works (jika feature ini ada)
- [ ] Navigation links work

### Setup Monitoring (Optional)
- [ ] Railway Metrics setup (CPU, Memory, Network)
- [ ] Log aggregation configured (jika needed)
- [ ] Alert setup jika critical errors (optional)

---

## 🔧 Common Issues & Quick Fixes

### Build Fails
```
Error: npm: command not found
→ Check: Dockerfile include RUN npm install
→ Fix: Update Dockerfile

Error: composer install fails
→ Check: composer.json syntax
→ Fix: Run locally: composer validate
```

### Deployment Fails
```
Error: Out of memory during build
→ Cause: Too many files or large dependencies
→ Fix: Use .dockerignore to exclude unnecessary files

Error: Cannot find module
→ Check: package.json dependencies
→ Fix: npm install locally first, commit package-lock.json
```

### Runtime Errors
```
Error: 500 Internal Server Error
→ Check: Laravel logs - railway logs
→ Fix: Check env variables, cache:clear

Error: Database connection refused
→ Check: DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD
→ Fix: Verify values di Railway Variables

Error: Migrations failed
→ Check: Migration files syntax
→ Fix: Run locally first: php artisan migrate --dry-run
```

### Access Issues
```
Error: Cannot access domain
→ Check: Domain DNS propagated
→ Fix: Wait 24h or check DNS settings

Error: 502 Bad Gateway
→ Check: Web service running (Railway Logs)
→ Fix: Check app startup errors
```

---

## 📊 Performance Checklist (Optional)

- [ ] Setup optimization:
  ```bash
  railway run php artisan optimize
  ```
- [ ] Enable query caching:
  - [ ] Update config/cache.php
  - [ ] Set CACHE_STORE to appropriate driver
- [ ] Setup CDN (optional):
  - [ ] For static assets (images, CSS, JS)
  - [ ] Use AWS CloudFront atau Cloudflare

---

## 🔐 Security Checklist

- [ ] `APP_DEBUG=false` di production ✓
- [ ] `APP_KEY` adalah unique & strong ✓
- [ ] Database credentials di Railway Secrets (not in code) ✓
- [ ] `.env` file di .gitignore ✓
- [ ] HTTPS enabled (Railway auto-provides) ✓
- [ ] CORS configured (config/cors.php) - jika API
- [ ] Rate limiting configured - jika public API
- [ ] CSRF protection enabled (default Laravel)

---

## 📞 Support & Escalation

If issues persist:

1. **Check Logs First**
   ```bash
   railway logs --tail
   # atau di Railway Dashboard → Logs
   ```

2. **Check Environment Variables**
   - Railway Dashboard → Variables tab
   - Compare dengan .env.production

3. **Restart Services**
   - Railway Dashboard → Service → Restart

4. **Rollback Deployment**
   - Railway Dashboard → Deployments → Previous successful deployment → Rollback

5. **Get Help**
   - Railway Support: https://railway.app/support
   - Laravel Community: https://laracasts.com
   - GitHub Issues: https://github.com/khokelo/tiktok-analysis/issues

---

## 📌 Notes

- **Deployment Duration**: 5-15 menit (tergantung build size)
- **Database Seeding**: Hanya perlu 1x (saat first deployment)
- **Asset Caching**: 24 jam (pas migrations, clear cache)
- **Environment Variables**: Update otomatis tanpa redeploy (mostly)
- **Rollback**: Semua deployments tersimpan, bisa rollback anytime

---

**Last Updated**: January 13, 2026
**Status**: ✅ Ready for Deployment
