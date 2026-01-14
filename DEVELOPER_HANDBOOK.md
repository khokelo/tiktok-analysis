# 👨‍💻 DEVELOPER HANDBOOK - TikTok Analysis

**Quick Reference for Development**

---

## 🚀 DAILY STARTUP (Every Time You Code)

### Terminal 1 - Backend
```bash
cd c:\Users\usama\OneDrive\Documents\GitHub\tiktok-analysis
php artisan serve
```

### Terminal 2 - Frontend
```bash
cd c:\Users\usama\OneDrive\Documents\GitHub\tiktok-analysis
npm run dev
```

### Terminal 3 - Optional (Database Shell)
```bash
cd c:\Users\usama\OneDrive\Documents\GitHub\tiktok-analysis
sqlite3 database/database.sqlite
```

**Then open:** http://localhost:8000

---

## 📝 COMMON ARTISAN COMMANDS

### Create New Files
```bash
php artisan make:controller HomeController
php artisan make:model Product
php artisan make:migration create_products_table
php artisan make:seeder ProductSeeder
php artisan make:middleware MyMiddleware
php artisan make:request ProductRequest
```

### Database Operations
```bash
php artisan migrate              # Apply pending migrations
php artisan migrate:rollback     # Undo last migration batch
php artisan migrate:fresh        # Reset entire database
php artisan db:seed              # Run all seeders
php artisan migrate:status       # Show migration status
```

### Cache & Queue
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan queue:work
```

### Utilities
```bash
php artisan route:list           # Show all routes
php artisan tinker               # Interactive shell
php artisan serve --port=8001    # Custom port
php artisan storage:link         # Link storage folder
```

---

## 💻 TINKER SHELL (Interactive)

```bash
php artisan tinker

# Database queries
>>> DB::table('users')->get()
>>> DB::table('sales')->count()
>>> DB::insert("INSERT INTO users ...")

# Eloquent models
>>> $user = User::find(1)
>>> $user->name = 'John'; $user->save()
>>> User::where('email', 'test@test.com')->first()

# Create records
>>> User::create(['name' => 'Jane', 'email' => 'jane@test.com', 'password' => bcrypt('pass')])

# Relationships
>>> $user->files()
>>> $user->files()->count()

# Debugging
>>> dd($variable)
>>> var_dump($data)
>>> echo json_encode($data, JSON_PRETTY_PRINT)

# Exit
>>> exit
```

---

## 📊 DATABASE OPERATIONS

### Insert Data
```bash
php artisan tinker

# Single insert
>>> DB::table('sales')->insert([
  'campaign' => 'Campaign A',
  'date' => '2024-01-15',
  'direct_gmv' => 1500.50,
  'items_sold' => 25,
  'customers' => 10,
  'viewers' => 500
])

# Bulk insert
>>> $data = [
  ['name' => 'User 1', 'email' => 'user1@test.com'],
  ['name' => 'User 2', 'email' => 'user2@test.com'],
]
>>> DB::table('users')->insert($data)
```

### Query Data
```bash
php artisan tinker

# Get all
>>> DB::table('sales')->get()

# With conditions
>>> DB::table('sales')->where('date', '2024-01-15')->get()

# Specific columns
>>> DB::table('users')->select('name', 'email')->get()

# Order & limit
>>> DB::table('sales')->orderBy('direct_gmv', 'desc')->take(10)->get()

# Aggregates
>>> DB::table('sales')->sum('direct_gmv')
>>> DB::table('sales')->avg('items_sold')
>>> DB::table('sales')->count()
```

### Update Data
```bash
php artisan tinker

# Update record
>>> DB::table('users')->where('id', 1)->update(['name' => 'New Name'])

# Using Eloquent
>>> $user = User::find(1)
>>> $user->update(['name' => 'New Name'])
>>> $user->save()
```

### Delete Data
```bash
php artisan tinker

# Delete with condition
>>> DB::table('users')->where('id', 1)->delete()

# Using Eloquent
>>> $user = User::find(1)
>>> $user->delete()
```

---

## 📁 FILE STRUCTURE REFERENCE

### Controllers Location
```
app/Http/Controllers/
├── HomeController.php
├── SalesController.php
├── UserController.php
└── ...
```

**Create:** `php artisan make:controller SalesController`

### Models Location
```
app/Models/
├── User.php
├── Sale.php
├── TiktokSale.php
├── UploadedFile.php
└── ...
```

**Create:** `php artisan make:model Sale`

### Migrations Location
```
database/migrations/
├── 0001_01_01_000000_create_users_table.php
├── 2026_01_01_000003_create_sales_table.php
└── ...
```

**Create:** `php artisan make:migration create_products_table`

### Routes Location
```
routes/
├── web.php (Web routes)
├── api.php (API routes)
└── auth.php (Auth routes)
```

### Views Location
```
resources/views/
├── welcome.blade.php
├── layouts/
│   └── app.blade.php
├── dashboard.blade.php
└── ...
```

---

## 🎨 BLADE TEMPLATING EXAMPLES

### Include CSS/JS with Vite
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### If/Else Statements
```blade
@if ($user->is_admin)
    <p>Admin controls visible</p>
@elseif ($user->is_moderator)
    <p>Moderator controls visible</p>
@else
    <p>Regular user view</p>
@endif
```

### Loops
```blade
@foreach ($users as $user)
    <p>{{ $user->name }} - {{ $user->email }}</p>
@endforeach

@forelse ($users as $user)
    <p>{{ $user->name }}</p>
@empty
    <p>No users found</p>
@endforelse
```

### Forms
```blade
<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Name">
    <input type="email" name="email" placeholder="Email">
    <button type="submit">Save</button>
</form>
```

### Links and Routes
```blade
<a href="{{ route('home') }}">Home</a>
<a href="{{ route('users.show', $user->id) }}">View User</a>
<a href="{{ url('/products') }}">Products</a>
```

### Display Variables
```blade
{{ $variable }}           {# Escaped #}
{!! $html !!}             {# Unescaped #}
{{ $var ?? 'default' }}   {# With default #}
```

---

## 🧪 QUICK TESTING ENDPOINTS

### Test Backend
```bash
# Using curl
curl http://localhost:8000/test
# Output: {"status":"ok"}

# Using browser
http://localhost:8000/
http://localhost:8000/test
http://localhost:8000/login
```

### API Testing (if available)
```bash
curl -X GET http://localhost:8000/api/health
curl -X POST http://localhost:8000/api/sales -H "Content-Type: application/json" -d '{"campaign":"Test"}'
```

---

## 🔍 DEBUGGING TECHNIQUES

### Laravel Log Files
```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# On Windows PowerShell
Get-Content storage/logs/laravel.log -Wait -Tail 50
```

### Debug in Code
```php
// In your controller or route
dd($variable);              // Dump and die
var_dump($data);           // Dump variable
Log::info('Debug', $data); // Log to file
Log::error('Error', $e);   // Log error

// Check logs
tail -f storage/logs/laravel.log
```

### Browser Console
```javascript
// Check frontend errors
// Press F12 → Console tab
// Look for JS errors

// Check Network tab for API calls
// F12 → Network → Reload page
// View request/response details
```

---

## 📦 PACKAGE MANAGEMENT

### Install New Composer Package
```bash
composer require vendor/package
composer require laravel/nova  # Example
```

### Install New NPM Package
```bash
npm install package-name
npm install tailwindcss-forms  # Example
npm run build  # Recompile
```

### Update Dependencies
```bash
composer update
npm update
```

### Remove Package
```bash
composer remove vendor/package
npm uninstall package-name
```

---

## ⚡ PERFORMANCE TIPS

### Optimize Queries
```php
// BAD - N+1 problem
foreach ($users as $user) {
    $files = $user->files();  // Query per user!
}

// GOOD - Eager loading
$users = User::with('files')->get();
foreach ($users as $user) {
    $files = $user->files;
}
```

### Cache Data
```php
// Cache for 1 hour
$users = Cache::remember('users', 3600, function () {
    return User::all();
});

// Clear cache
Cache::forget('users');
Cache::flush();
```

### Database Indexing
```php
// In migration
Schema::create('sales', function (Blueprint $table) {
    $table->id();
    $table->string('campaign')->index();  // Add index
    $table->date('date')->index();
    $table->timestamps();
});
```

---

## 🔐 SECURITY CHECKLIST

### Always Validate Input
```php
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8|confirmed',
]);
```

### Use CSRF Protection
```blade
<form method="POST" action="/store">
    @csrf
    <!-- form inputs -->
</form>
```

### Escape Output
```blade
{{ $user->name }}      {# Always escape #}
{!! $html !!}          {# Only for trusted HTML #}
```

### Hash Passwords
```php
'password' => Hash::make($password),
```

### Use Authorization
```php
// In controller
if (!$user->can('edit', $post)) {
    abort(403);
}
```

---

## 🐛 COMMON ERRORS & FIXES

| Error | Cause | Fix |
|-------|-------|-----|
| `Class not found` | Incorrect namespace or not imported | Check `use` statements and run `composer dump-autoload` |
| `Table not found` | Migration not run | Run `php artisan migrate` |
| `Port in use` | Another process using port | Use `--port=8001` or kill process |
| `CSRF token mismatch` | Missing @csrf in form | Add @csrf to POST forms |
| `401 Unauthorized` | Not authenticated | Login first or check auth middleware |
| `419 Page Expired` | Session expired | Login again |
| `500 Server Error` | Code error | Check `storage/logs/laravel.log` |

---

## 🔧 USEFUL DEVELOPMENT TOOLS

### VS Code Extensions
- PHP Intelephense
- Tailwind CSS IntelliSense
- ES7+ React/Redux/React-Native snippets
- Thunder Client (API testing)
- SQLite Viewer

### Browser Tools
- Vue DevTools
- React DevTools
- Laravel Debugbar

### Command Line
```bash
# Format code
./vendor/bin/pint

# Run tests
php artisan test

# Check code quality
./vendor/bin/phpstan analyse
```

---

## 📚 QUICK REFERENCE LINKS

- **Laravel Docs:** https://laravel.com/docs
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Vite:** https://vitejs.dev
- **SQLite:** https://sqlite.org/docs.html
- **PHP Docs:** https://www.php.net/manual

---

## ✅ DAILY CHECKLIST

- [ ] Start backend server
- [ ] Start frontend server
- [ ] Test main route (http://localhost:8000)
- [ ] Check browser console for errors
- [ ] Check terminal for warnings
- [ ] Commit changes to git
- [ ] Test database queries if making changes

---

## 💾 GIT WORKFLOW

### Commit Changes
```bash
git status
git add .
git commit -m "Description of changes"
git push origin main
```

### Create Feature Branch
```bash
git checkout -b feature/feature-name
# Make changes
git add .
git commit -m "Add new feature"
git push origin feature/feature-name
# Create pull request
```

### Rollback Changes
```bash
git log --oneline
git revert <commit-hash>
git reset --hard HEAD~1  # Undo last commit
```

---

## 🚀 PRODUCTION CHECKLIST

- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production`
- [ ] Run `composer install --no-dev`
- [ ] Run `npm run build`
- [ ] Run migrations
- [ ] Clear all caches
- [ ] Set strong database password
- [ ] Backup database
- [ ] Setup HTTPS
- [ ] Monitor error logs

---

**Save this file for quick reference during development!**

**Last Updated:** Today  
**Status:** ✅ Ready to use
