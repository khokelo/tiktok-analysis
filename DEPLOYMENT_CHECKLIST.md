# 🎯 Railway Deployment - Deployment Ready Checklist

Checklist lengkap sebelum production deployment.

---

## ✅ Pre-Deployment Checklist

### Code Quality
- [ ] Semua tests passing: `php artisan test`
- [ ] No PHP errors: `composer validate`
- [ ] No linting errors: `npm run build` successful
- [ ] Database migrations ready: `php artisan migrate:status`
- [ ] All routes working: `php artisan route:list`

### Local Testing
- [ ] App runs locally: `php artisan serve`
- [ ] Login works with test credentials
- [ ] CSV upload works
- [ ] Dashboard charts display correctly
- [ ] Dark mode toggle works
- [ ] Admin panel accessible
- [ ] All database operations working

### Environment Setup
- [ ] `.env` configured with correct values
- [ ] `APP_KEY` generated: `php artisan key:generate --show`
- [ ] Database connection tested
- [ ] Storage directory writable
- [ ] Cache directory writable

### Git & GitHub
- [ ] Code committed: `git status` is clean
- [ ] Branch is `main` (not `master`)
- [ ] Remote is set: `git remote -v`
- [ ] Push successful: `git push origin main`
- [ ] GitHub Actions workflow file exists
- [ ] GitHub Secrets added:
  - [ ] `RAILWAY_TOKEN`
  - [ ] `APP_KEY`
  - [ ] `RAILWAY_APP_URL` (can be empty initially)

### Railway Setup
- [ ] Railway account created
- [ ] Project created in Railway
- [ ] MySQL/Database service added
- [ ] Environment variables configured
- [ ] Database credentials verified

### Documentation
- [ ] `DEPLOYMENT_GUIDE.md` reviewed
- [ ] `QUICK_START.md` reviewed
- [ ] `TROUBLESHOOTING.md` bookmarked
- [ ] `GITHUB_SECRETS_SETUP.md` reviewed

---

## 🚀 Deployment Steps (In Order)

### Step 1: Final Local Verification (5 min)

```bash
cd /path/to/project

# Check everything
git status                  # Should be clean
php artisan test           # All tests pass
npm run build              # No errors
php artisan serve          # Test locally
```

### Step 2: Prepare GitHub (5 min)

```bash
# Ensure code is committed
git add .
git commit -m "Production deployment"

# Push to main branch
git push origin main

# Verify on GitHub
# → Go to https://github.com/USERNAME/tiktok-analysis
# → Verify latest commit is there
```

### Step 3: Setup GitHub Secrets (3 min)

1. Go to: **Repository → Settings → Secrets and variables → Actions**
2. Add three secrets:

```
RAILWAY_TOKEN
Value: [from Railway Dashboard → Account Settings → Tokens]

APP_KEY
Value: [from php artisan key:generate --show]

RAILWAY_APP_URL
Value: [leave empty for now, update after first deployment]
```

### Step 4: Monitor GitHub Actions (2 min)

1. Go to: **Repository → Actions**
2. Watch "Deploy to Railway" workflow
3. Steps:
   - Test job: Runs PHP tests ⏱️ ~2-3 min
   - Deploy job: Deploys to Railway ⏱️ ~3-5 min
4. Wait for both to complete (green ✓)

### Step 5: Setup Railway (5 min)

1. Go to: https://railway.app
2. Create new project from GitHub:
   - New Project → GitHub Repo → Select `tiktok-analysis`
3. Add MySQL service:
   - Services → Add → MySQL
4. Configure environment variables (in Railway dashboard):

```
APP_NAME=TikTok_Analysis
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx...
APP_URL=https://your-app-name.railway.app

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=[from Railway]
```

### Step 6: Verify Deployment (5 min)

```bash
# Wait for Railway to finish deployment (green status)

# Test health endpoint:
curl https://your-app-name.railway.app/health

# Expected response:
# {"status":"healthy","database":"connected",...}

# Test login:
# Visit https://your-app-name.railway.app
# Login dengan admin account
```

### Step 7: Post-Deployment Tasks (10 min)

```bash
# In Railway Command line (Services → [App] → Command):

# Run migrations (if not already done)
php artisan migrate --force

# Seed admin user
php artisan db:seed --class=AdminUserSeeder

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:cache

# Verify database
php artisan tinker
>>> App\Models\User::count()
>>> exit
```

### Step 8: Update GitHub Secret

Update `RAILWAY_APP_URL` secret:

1. Get URL from Railway:
   - Railway Dashboard → Services → [Your App] → Domains
   - Copy the URL

2. Update GitHub secret:
   - Repository → Settings → Secrets → Update `RAILWAY_APP_URL`
   - Paste the URL

---

## 📊 Expected Timeline

```
Total time: ~30 minutes

Step 1 (Local verification):     5 min  ✓
Step 2 (Push to GitHub):         5 min  ✓
Step 3 (Add secrets):            3 min  ✓
Step 4 (Monitor CI/CD):          5 min  ✓
Step 5 (Railway setup):          5 min  ✓
Step 6 (Verify deployment):      5 min  ✓
Step 7 (Post-deploy tasks):     10 min  ✓
Step 8 (Update secrets):         2 min  ✓
                               --------
                          TOTAL: ~30 min
```

---

## 🔍 Verification Checklist (After Deployment)

### Application Accessible
- [ ] Website loads: `https://your-app.railway.app`
- [ ] Health check works: `https://your-app.railway.app/health`
- [ ] Login page visible

### Authentication
- [ ] Admin login works
- [ ] User login works
- [ ] Logout works
- [ ] Session persists across pages

### User Dashboard
- [ ] Dashboard loads
- [ ] All charts render
- [ ] CSV upload works
- [ ] Dark mode toggle works
- [ ] Data displays correctly

### Admin Panel
- [ ] Admin dashboard accessible
- [ ] Sales management works
- [ ] User management works
- [ ] File management works
- [ ] Dark mode toggle works

### Database
- [ ] Data persists after page reload
- [ ] Migrations completed successfully
- [ ] No database errors in logs

### Performance
- [ ] Pages load in < 3 seconds
- [ ] Charts render smoothly
- [ ] No console errors in browser

---

## 📈 Monitoring After Deployment

### Daily Checks
```bash
# Check logs
# Railway Dashboard → Logs → Review last 24 hours

# Check health
curl https://your-app.railway.app/health

# Monitor errors
# Railway → Logs → Filter for "ERROR"
```

### Weekly Checks
- Review GitHub Actions workflow runs
- Check Railway deployment history
- Monitor database size
- Review error logs for patterns

### Monthly Maintenance
```bash
# Update dependencies
git pull origin main
composer update
npm update
git commit -am "Update dependencies"
git push origin main
# GitHub Actions auto-deploys

# Backup database
# Railway → Services → MySQL → Advanced → Backup
```

---

## 🆘 Emergency Recovery

### If Deployment Fails

```bash
# Option 1: Rollback code
git revert HEAD
git push origin main
# GitHub Actions will auto-deploy reverted code

# Option 2: Revert in Railway
# Railway → Deployments → Select previous successful → Redeploy
```

### If Database Issues

```bash
# In Railway Command line:
php artisan migrate:refresh --force  # ⚠️ Deletes all data!
php artisan db:seed --force
```

### If Website Not Accessible

```bash
# Check application status
# Railway → Services → [App] → Status should be "Running"

# If not running:
# Railway → Restart button

# Check logs for errors
# Railway → Logs
```

---

## 📚 Documentation Links

- **Comprehensive Guide**: [DEPLOYMENT_GUIDE.md](./DEPLOYMENT_GUIDE.md)
- **Quick Start (10 min)**: [QUICK_START.md](./QUICK_START.md)
- **Troubleshooting**: [TROUBLESHOOTING.md](./TROUBLESHOOTING.md)
- **GitHub Secrets**: [GITHUB_SECRETS_SETUP.md](./GITHUB_SECRETS_SETUP.md)

---

## ✨ Success Indicators

Deployment berhasil jika:

- ✅ Website accessible di Railway URL
- ✅ Health endpoint returns "healthy"
- ✅ Admin login works
- ✅ Dashboard displays data and charts
- ✅ CSV upload functions
- ✅ No red errors in Railway logs
- ✅ Database operations working
- ✅ Assets loading (CSS, JS)

---

## 🎓 Next Steps After Deployment

1. **Monitor for 24 hours**
   - Watch logs for any errors
   - Test critical features
   - Monitor performance

2. **Setup monitoring (optional)**
   - Railway email notifications
   - Error tracking (Sentry)
   - Performance monitoring (New Relic)

3. **Plan regular updates**
   - Update dependencies monthly
   - Security patches immediately
   - Feature updates on schedule

4. **Backup strategy**
   - Database backups daily
   - Code backups (Git)
   - Asset backups (if needed)

---

**Status**: 🟢 DEPLOYMENT READY
**Last Updated**: 2024
**Version**: 1.0

---

## 📞 Support

Jika mengalami masalah:

1. Check [TROUBLESHOOTING.md](./TROUBLESHOOTING.md)
2. Review Railway logs
3. Review GitHub Actions logs
4. Test locally first: `php artisan serve`
5. Git revert if needed: `git revert HEAD && git push`
