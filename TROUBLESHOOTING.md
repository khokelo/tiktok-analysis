# 🔧 Troubleshooting Guide - Railway Deployment Issues

Panduan lengkap untuk mengatasi masalah deployment ke Railway.

## 🚨 Common Issues & Solutions

---

## 1️⃣ Database Connection Failed

### ❌ Error Message
```
SQLSTATE[HY000] [1045] Access denied for user 'root'
Connection refused
Cannot connect to database
```

### ✅ Solutions

**Check 1: Verify Environment Variables**
```bash
# Di Railway Dashboard:
1. Click project → Variables tab
2. Verify:
   - DB_HOST=mysql.railway.internal (not localhost)
   - DB_PORT=3306
   - DB_USERNAME & DB_PASSWORD match MySQL service
   - DB_DATABASE exists
```

**Check 2: MySQL Service Running**
```bash
# Di Railway Dashboard:
1. Services tab → Check MySQL status (should be green)
2. If red, click MySQL → Restart
```

**Check 3: Connection String Format**
```bash
# Wrong ❌
DB_HOST=localhost
DB_HOST=127.0.0.1
DB_HOST=db.railway.internal

# Correct ✅
DB_HOST=mysql.railway.internal
```

**Check 4: Test Connection Lokal**
```bash
# Test dengan credentials yang sama
mysql -h mysql.railway.internal -u root -p[password] -e "SELECT 1"
```

**Check 5: Run Migration Manually**
```bash
# Di Railway → Services → [Your App] → Command line
php artisan migrate --force --verbose

# Check migration history
php artisan migrate:status
```

---

## 2️⃣ GitHub Actions Test Failing

### ❌ Error Message
```
❌ Tests failed
Process exited with code 1
FAIL: ...
```

### ✅ Solutions

**Check 1: Run Tests Lokal Dulu**
```bash
# Di local machine
php artisan test

# Jika ada error, fix sebelum push
# Commit fixed code
git add .
git commit -m "Fix failing tests"
git push origin main
```

**Check 2: Check GitHub Actions Logs**
```
Repository → Actions → [Latest workflow] → test job → Logs
```

**Check 3: Common Test Failures**

```php
// Error: Route not found
// Solution: Update route references

// Error: Database error in tests
// Solution: Check SQLite test database is writable

// Error: Cache driver error
// Solution: Tests use file cache driver automatically
```

**Check 4: Skip Tests Temporarily**

Edit `.github/workflows/deploy-main.yml`:

```yaml
# TEMPORARY - remove after fixing tests
- name: Run tests
  run: echo "Tests disabled for debugging"
  # Commented out:
  # run: php artisan test
```

⚠️ **Note**: Re-enable tests setelah fix!

---

## 3️⃣ Assets Not Loading (404 Errors)

### ❌ Error Message
```
GET /build/assets/app-xyz.js 404
Tailwind styles not loading
JavaScript not working
```

### ✅ Solutions

**Check 1: Verify npm Build Success**
```bash
# In GitHub Actions logs:
✓ npm install
✓ npm run build
✓ public/build directory exists
```

**Check 2: Check public/build Directory**
```bash
# Local
ls -la public/build/
# Should have manifest.json and assets/ folder

# If missing:
npm run build
git add public/build/
git commit -m "Rebuild assets"
git push origin main
```

**Check 3: Verify APP_URL in .env**
```env
# Correct
APP_URL=https://your-app.railway.app
APP_URL=https://your-app-name.up.railway.app

# Wrong (will cause asset paths to be wrong)
APP_URL=localhost:8000
APP_URL=127.0.0.1
```

**Check 4: Clear Caches**
```bash
# In Railway Command line:
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

**Check 5: Rebuild Assets**
```bash
# Local
npm install
npm run build

# Commit and push
git add public/build/
git commit -m "Rebuild assets"
git push origin main
```

---

## 4️⃣ 500 Internal Server Error

### ❌ Error Message
```
500 Internal Server Error
Something went wrong
```

### ✅ Solutions

**Check 1: View Application Logs**
```bash
# Railway → Services → [Your App] → Logs
# Look for PHP errors

# Or SSH and check:
php artisan logs
tail storage/logs/laravel.log
```

**Check 2: Enable Debug Mode (Temporary)**
```env
# In Railway Variables:
APP_DEBUG=true  # ONLY for debugging!
```

**Check 3: Common 500 Errors**

```
Error: Class not found
→ Fix: Verify class exists, run: composer install -o

Error: Database connection
→ Fix: Check database credentials (see Issue #1)

Error: Permission denied
→ Fix: Check storage permissions

Error: Out of memory
→ Fix: Increase PHP memory_limit
```

**Check 4: Check Storage Permissions**
```bash
# In Railway Command line:
chmod -R 775 storage bootstrap/cache
```

---

## 5️⃣ Health Check Failing

### ❌ Error Message
```
Health check failed
GET /health returned 500
Deployment unhealthy
```

### ✅ Solutions

**Check 1: Test Health Endpoint Lokal**
```bash
# Local
php artisan serve

# In another terminal
curl http://localhost:8000/health

# Should return:
# {"status":"healthy","timestamp":"...","database":"connected"}
```

**Check 2: Verify Health Route Exists**
```bash
# In routes/web.php
Route::get('/health', function () {
    // Should be there
});
```

**Check 3: Check Database Connection in Health Check**
```php
// routes/web.php
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $status = 'connected';
    } catch (\Exception $e) {
        $status = 'disconnected';
    }
    
    return response()->json([
        'status' => 'healthy',
        'database' => $status,
    ]);
});
```

---

## 6️⃣ Deployment Takes Too Long or Times Out

### ❌ Error Message
```
Deployment timeout
Build taking longer than expected
Deployment cancelled
```

### ✅ Solutions

**Check 1: Check Build Logs**
```bash
# Railway → Deployments → [Your Deploy] → Logs
# Look for slow steps (npm install, composer, migrations)
```

**Check 2: Optimize npm Install**
```bash
# In .github/workflows/deploy-main.yml
- run: npm ci --legacy-peer-deps  # Instead of npm install

# Or add caching:
- uses: actions/setup-node@v3
  with:
    node-version: '18'
    cache: 'npm'
```

**Check 3: Optimize Composer Install**
```bash
# Add to workflow:
- uses: shivammathur/setup-php@v2
  with:
    php-version: '8.2'
    extensions: mbstring,json,sqlite
    coverage: none
```

**Check 4: Skip Heavy Steps**

```yaml
# Temporary - comment out slow steps
- name: Run migrations
  run: echo "Skipping migrations for debugging"
  # run: php artisan migrate --force
```

---

## 7️⃣ Environment Variables Not Applied

### ❌ Error
```
APP_NAME not set
APP_KEY not found
DB_HOST missing
```

### ✅ Solutions

**Check 1: Verify Variables Added to Railway**
```bash
# Railway Dashboard → Project → Variables
# Confirm all variables are listed
```

**Check 2: Restart Deployment After Changing Variables**
```bash
# Railway → Deployments → Redeploy latest
# Or push a new commit to trigger deployment
```

**Check 3: Clear Config Cache**
```bash
# In Railway Command line:
php artisan config:cache --force
php artisan cache:clear
```

**Check 4: Verify Variable Format**
```bash
# Check in Railway logs:
# "APP_NAME is set to: TikTok_Analysis"
# "DB_HOST is set to: mysql.railway.internal"
```

---

## 8️⃣ GitHub Actions Workflow Not Triggering

### ❌ Problem
```
No deployment after push
Actions tab shows no recent runs
```

### ✅ Solutions

**Check 1: Verify Workflow File**
```bash
# File must exist: .github/workflows/deploy-main.yml
# And be valid YAML (no syntax errors)

# Test locally:
# yaml-lint .github/workflows/deploy-main.yml
```

**Check 2: Check Branch Trigger**
```yaml
# .github/workflows/deploy-main.yml
on:
  push:
    branches:
      - main  # Must be 'main' not 'master'
```

**Check 3: Verify Push to Main Branch**
```bash
# Make sure you're pushing to correct branch
git branch  # Check current branch (should have *)
git push origin main  # Explicitly push to main
```

**Check 4: Check for Branch Protection**
```bash
# GitHub → Settings → Branches
# Verify main branch doesn't require approval
```

---

## 9️⃣ Cannot Login After Deployment

### ❌ Problem
```
Login fails
401 Unauthorized
Invalid credentials
```

### ✅ Solutions

**Check 1: Verify Admin User Exists**
```bash
# In Railway Command line:
php artisan tinker
>>> App\Models\User::where('email', 'admin@example.com')->first()
```

**Check 2: Seed Admin User**
```bash
# In Railway Command line:
php artisan db:seed --class=AdminUserSeeder
```

**Check 3: Check Database Migration**
```bash
# Verify users table exists:
php artisan migrate:status
# Should show all migrations as "yes"
```

**Check 4: Verify Authentication Middleware**
```php
// routes/web.php
Route::middleware('auth')->group(function () {
    // Protected routes
});
```

---

## 🔟 Deployment Succeeded but App Not Responding

### ❌ Problem
```
Deployment shows "Success"
But website times out or not accessible
```

### ✅ Solutions

**Check 1: Verify App is Running**
```bash
# Railway → Services → [Your App]
# Status should be green "Running"

# If not running, click "Restart"
```

**Check 2: Check App Logs**
```bash
# Railway → Logs
# Look for errors preventing startup
```

**Check 3: Check Port Configuration**
```bash
# railway.json should have:
# "startCommand": "... --port=$PORT"

# Verify PORT env variable set:
# Should be 3000, 8080, or auto-assigned
```

**Check 4: Test Health Endpoint**
```bash
# Get app URL from Railway
# Test in browser or curl:
curl https://your-app.railway.app/health
```

**Check 5: SSH and Check Manually**
```bash
# Railway → Services → [Your App] → Command line
# Test if Laravel is running:
php artisan serve --port=8000

# Or check processes:
ps aux | grep php
```

---

## 🛠️ Recovery Procedures

### Rollback to Previous Deployment

```bash
# Option 1: Use Railway UI
Railway Dashboard → Deployments → [Previous successful] → Redeploy

# Option 2: Git revert
git revert HEAD
git push origin main
# GitHub Actions will auto-deploy reverted code
```

### Force Fresh Deployment

```bash
# Create empty commit
git commit --allow-empty -m "Force deployment"
git push origin main
```

### Reset Database

```bash
# IN PRODUCTION - Be careful!
# Railway → Services → MySQL → Advanced

# Or via command line:
php artisan migrate:refresh --force  # WARNING: Deletes all data!
php artisan db:seed --force
```

---

## 📞 Getting More Help

### Check Logs

1. **GitHub Actions Logs**
   - Repository → Actions → [Workflow] → Logs
   - Search for "Error" or "Exception"

2. **Railway Logs**
   - Railway Dashboard → Services → [App] → Logs
   - Real-time log streaming

3. **Local Logs**
   - `storage/logs/laravel.log`
   - `php artisan logs`

### Test Locally First

```bash
# Always test changes locally before pushing:
1. git pull origin main
2. composer install
3. npm install && npm run build
4. php artisan migrate
5. php artisan serve
6. Test features manually
7. php artisan test (run tests)
8. git commit and push only if everything works
```

### Common Commands

```bash
# Useful debugging commands
php artisan config:show    # Show all config
php artisan env            # Show current environment
php artisan route:list     # List all routes
php artisan migrate:status # Check migration status
php artisan logs          # Show error logs
php artisan tinker        # Interactive shell
```

---

**Last Updated**: 2024
**Version**: 1.0
**Confidence**: High
