# 🧪 Testing Guide & Troubleshooting

## ✅ Pre-Launch Checklist

### Database
- [x] Migrations run successfully
- [x] Tables created (users, uploaded_files, tiktok_sales)
- [x] Seeders executed (admin user created)

### Configuration
- [x] .env file configured
- [x] APP_KEY generated
- [x] Database connection working
- [x] Middleware registered

### Dependencies
- [x] Composer packages installed
- [x] Chart.js CDN available
- [x] TailwindCSS loaded
- [x] Blade views compiled

---

## 🔍 Manual Testing Steps

### Test 1: Admin Login
**Steps:**
1. Open `http://localhost:8000`
2. Click Login or go to `/login`
3. Enter credentials:
   - Email: `admin@email.com`
   - Password: `admin123`
4. Click "Login"

**Expected Result:**
- ✅ Login successful
- ✅ Redirect to `/dashboard`
- ✅ Can see user dashboard

**If Failed:**
```bash
# Check admin user exists
php artisan tinker
>>> User::where('email', 'admin@email.com')->first()

# If not found, re-seed
php artisan db:seed --class=AdminUserSeeder
```

---

### Test 2: Access Admin Panel
**Steps:**
1. From logged-in user dashboard
2. Click navbar or navigate to `/admin`
3. Should see Admin Dashboard

**Expected Result:**
- ✅ Admin dashboard loads
- ✅ Sidebar visible with menu items
- ✅ 4 statistics cards visible
- ✅ Charts render without errors

**If Failed:**
```bash
# Check routes registered
php artisan route:list | grep admin

# Check middleware
php artisan route:list --path=/admin

# Browser console - check for JS errors
```

---

### Test 3: Charts Rendering
**Steps:**
1. On admin dashboard
2. Wait for page to fully load
3. Check each chart (GMV, Items, Campaign, Upload)

**Expected Result:**
- ✅ All 4 charts display
- ✅ Charts have data and labels
- ✅ Hover tooltip works
- ✅ Legend visible

**If Failed:**
```bash
# Check browser console for errors
# Check Chart.js CDN is accessible
# Verify chart data in page source

# Debug in tinker
php artisan tinker
>>> TiktokSale::count()
>>> User::count()
>>> UploadedFile::count()
```

---

### Test 4: User Management CRUD

#### Create User
**Steps:**
1. Go to `/admin/users`
2. Click "Tambah User"
3. Fill form:
   ```
   Nama: Test User
   Email: testuser@email.com
   Password: testpass123
   Password Confirm: testpass123
   Role: user
   ```
4. Click "Tambah User"

**Expected Result:**
- ✅ User created successfully
- ✅ Redirected to users list
- ✅ New user appears in table
- ✅ Can login with new credentials

**Test Login:**
```bash
# Try login with new user
Email: testuser@email.com
Password: testpass123
```

#### Read Users
**Steps:**
1. Go to `/admin/users`
2. Check pagination, sorting

**Expected Result:**
- ✅ All users listed
- ✅ Pagination works (if >15 users)
- ✅ Can click user name to view details

#### Update User
**Steps:**
1. Go to `/admin/users`
2. Click "Edit" for any user
3. Change name: `Updated Name`
4. Click "Update User"

**Expected Result:**
- ✅ User updated
- ✅ Changes reflected in list

#### Delete User
**Steps:**
1. Go to `/admin/users`
2. Click "Hapus" for a test user
3. Confirm deletion

**Expected Result:**
- ✅ Confirmation dialog shows
- ✅ User deleted from database
- ✅ Removed from table

**Cannot Delete Self:**
- ✅ Try to delete current admin
- ✅ Should show error message

---

### Test 5: File Management CRUD

#### Upload File
**Steps:**
1. Go to user dashboard (`/dashboard`)
2. Select a CSV file
3. Click "Upload CSV"

**Expected Result:**
- ✅ File uploaded
- ✅ Data processed
- ✅ Success message shows
- ✅ File record created in `uploaded_files` table

**Check in Admin:**
1. Go to `/admin/files`
2. Should see uploaded file in list

#### View File Details
**Steps:**
1. Go to `/admin/files`
2. Click file name or "View"
3. Should see detailed view

**Expected Result:**
- ✅ File info displays
- ✅ Uploader info shows
- ✅ Data preview visible (10 rows)
- ✅ Download button works

#### Update File Status
**Steps:**
1. On file list, click status dropdown
2. Change from "Pending" to "Processed"

**Expected Result:**
- ✅ Status updates
- ✅ Badge color changes
- ✅ Toast notification shows

#### Bulk Delete Files
**Steps:**
1. Go to `/admin/files`
2. Check 2-3 files
3. Click "Hapus Terpilih"
4. Confirm deletion

**Expected Result:**
- ✅ Files deleted
- ✅ Count reduced
- ✅ Physical files removed from `/storage/app/public/uploads`

#### Download File
**Steps:**
1. Go to `/admin/files`
2. Click "Download"

**Expected Result:**
- ✅ File downloads
- ✅ Original filename preserved
- ✅ File content intact

---

### Test 6: Role-Based Access

#### Admin Can Access
```
✅ /admin
✅ /admin/users
✅ /admin/files
✅ /dashboard (user area)
```

#### Non-Admin Cannot Access
```bash
# Create non-admin user first
# Login with that user
# Try to access /admin

# Should get 403 Forbidden error
# Redirect to /login or error page
```

#### Try Demote Self
**Steps:**
1. Admin clicks own name in users list
2. Try to edit and change role to "user"
3. Click update

**Expected Result:**
- ✅ Error message: Cannot change own role to user
- ✅ Change prevented

---

### Test 7: Analytics & Insights

**Verify Data:**
1. On admin dashboard
2. Check "Key Insights" cards show:
   - Top campaign
   - Top campaign GMV
   - User count breakdown
   - File statistics

**Expected Result:**
- ✅ Data matches database queries
- ✅ Numbers are accurate
- ✅ All sections populated

---

## 🐛 Troubleshooting

### Issue 1: "Admin access only" error
**Symptoms:** Cannot access `/admin` even as admin

**Solutions:**
```bash
# 1. Check role in database
php artisan tinker
>>> auth()->user()->role

# 2. Verify role is 'admin'
>>> auth()->user()->update(['role' => 'admin'])

# 3. Clear session and re-login
php artisan cache:clear
```

### Issue 2: Charts not displaying
**Symptoms:** Blank spaces where charts should be

**Solutions:**
```bash
# 1. Check browser console for JS errors
# Press F12 → Console

# 2. Verify Chart.js loaded
# Check if CDN is accessible

# 3. Check chart data in page source
# Ctrl+U → Search for "gmvChart"

# 4. Clear browser cache
# Ctrl+Shift+Delete → Clear all
```

### Issue 3: CSV file not appearing in file list
**Symptoms:** Upload success but file not in `/admin/files`

**Solutions:**
```bash
# 1. Check table directly
php artisan tinker
>>> UploadedFile::all()

# 2. Check if user_id correct
>>> UploadedFile::latest()->first()

# 3. Check file permissions
# Verify /storage/app/public/uploads writable

# 4. Run migration again
php artisan migrate --force
```

### Issue 4: Delete operation fails
**Symptoms:** "Could not delete file"

**Solutions:**
```bash
# 1. Check file exists
ls storage/app/public/uploads/

# 2. Check permissions
chmod -R 755 storage/

# 3. Create symlink if missing
php artisan storage:link

# 4. Check physical file path
php artisan tinker
>>> UploadedFile::find(1)->file_path
```

### Issue 5: CSRF token validation fails
**Symptoms:** "419 Page Expired" error

**Solutions:**
```bash
# 1. Clear application cache
php artisan cache:clear

# 2. Clear sessions
php artisan session:flush

# 3. Regenerate app key
php artisan key:generate

# 4. Check .env SESSION_DRIVER
# Should be: SESSION_DRIVER=file or database
```

### Issue 6: Database connection error
**Symptoms:** "SQLSTATE[HY000]: General error"

**Solutions:**
```bash
# 1. Check .env database settings
DB_HOST=127.0.0.1
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=

# 2. Test connection
php artisan tinker
>>> DB::connection()->getPdo()

# 3. Run migrations
php artisan migrate --force

# 4. Check MySQL running
# Windows: Check MySQL service
```

### Issue 7: Middleware not working
**Symptoms:** Non-admin can access `/admin`

**Solutions:**
```bash
# 1. Check middleware registered
cat bootstrap/app.php | grep AdminMiddleware

# 2. Check route middleware
php artisan route:list --path=/admin

# 3. Verify user role
php artisan tinker
>>> User::first()->role

# 4. Clear route cache
php artisan route:clear
php artisan config:clear
```

---

## 📊 Data Validation Tests

### CSV Upload Format
**Valid CSV:**
```csv
Campaign,Date,Time,Direct GMV,Items sold,Customers,Viewers
Campaign A,2026-01-10,14:30,50000,100,50,500
Campaign B,2026-01-11,15:45,60000,120,60,600
```

**Test Invalid CSV:**
- Wrong column names
- Missing columns
- Invalid date format
- Non-numeric GMV

**Expected:** Should show error or skip invalid rows

### User Form Validation
**Test Cases:**
```
1. Empty name → Error: "Name required"
2. Invalid email → Error: "Invalid email"
3. Duplicate email → Error: "Email already exists"
4. Password < 8 chars → Error: "Min 8 characters"
5. Passwords don't match → Error: "Passwords don't match"
```

### File Status Update
**Test Cases:**
```
1. Valid status: pending → Success
2. Valid status: processed → Success
3. Valid status: failed → Success
4. Invalid status: "unknown" → 422 Error
```

---

## 🚀 Performance Testing

### Load Test
```bash
# Install Apache Bench
apt-get install apache2-utils

# Test dashboard
ab -n 100 -c 10 http://localhost:8000/admin

# Expected: <500ms response time
```

### Database Query Performance
```bash
# Enable query logging
php artisan tinker
>>> DB::enableQueryLog()
>>> Dashboard load
>>> dd(DB::getQueryLog())

# Should see <20 queries
```

---

## ✨ Success Indicators

- [x] Admin login works
- [x] Dashboard displays with data
- [x] Charts render correctly
- [x] User CRUD operations functional
- [x] File upload & tracking works
- [x] File management operations work
- [x] Role-based access control enforced
- [x] No console errors
- [x] All validations working
- [x] Database operations smooth

---

## 📝 Test Report Template

```
Date: [DATE]
Tester: [NAME]
Environment: [LOCAL/STAGING/PRODUCTION]

✅ PASSED:
- Feature 1
- Feature 2

❌ FAILED:
- Issue description
- Steps to reproduce
- Expected vs Actual

⚠️ NOTES:
- Performance observations
- Suggestions for improvement
```

---

**Testing Complete!** 🎉

For detailed issues, check:
- `/storage/logs/laravel.log`
- Browser DevTools Console
- Database logs

---

**Last Updated**: January 13, 2026
**Version**: 1.0.0
