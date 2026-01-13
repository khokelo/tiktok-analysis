<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\FileManagementController;
use App\Http\Controllers\Admin\SalesManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Health check endpoint for Railway monitoring
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Diagnostic endpoint - shows actual errors
Route::get('/diag', function () {
    $info = [
        'app_env' => env('APP_ENV'),
        'app_debug' => env('APP_DEBUG'),
        'app_key_set' => !empty(env('APP_KEY')),
    ];
    
    try {
        $info['db_connection'] = DB::connection()->getName();
        $info['db_tables'] = DB::select('SHOW TABLES');
    } catch (\Throwable $e) {
        $info['db_error'] = $e->getMessage();
    }
    
    return response()->json($info);
});

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/upload-csv', [DashboardController::class, 'upload'])->name('upload.csv');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Route Sales Import
    Route::post('/import', [SaleController::class, 'store'])->name('sales.import');

    // Route Manajemen Sales (Admin)
    Route::get('/admin/sales', [SalesManagementController::class, 'index'])->name('admin.sales.index');
    Route::get('/admin/sales/create', [SalesManagementController::class, 'create'])->name('admin.sales.create');
    Route::post('/admin/sales', [SalesManagementController::class, 'store'])->name('admin.sales.store');
    Route::get('/admin/sales/{sale}', [SalesManagementController::class, 'show'])->name('admin.sales.show');
    Route::get('/admin/sales/{sale}/edit', [SalesManagementController::class, 'edit'])->name('admin.sales.edit');
    Route::put('/admin/sales/{sale}', [SalesManagementController::class, 'update'])->name('admin.sales.update');
    Route::delete('/admin/sales/{sale}', [SalesManagementController::class, 'destroy'])->name('admin.sales.destroy');
    Route::post('/admin/sales/bulk-delete', [SalesManagementController::class, 'bulkDelete'])->name('admin.sales.bulkDelete');

    // Route Manajemen User
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}', [UserManagementController::class, 'show'])->name('admin.users.show');
    Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/admin/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('admin.users.updateRole');

    // Route Manajemen File
    Route::get('/admin/files', [FileManagementController::class, 'index'])->name('admin.files.index');
    Route::get('/admin/files/{file}', [FileManagementController::class, 'show'])->name('admin.files.show');
    Route::put('/admin/files/{file}', [FileManagementController::class, 'update'])->name('admin.files.update');
    Route::delete('/admin/files/{file}', [FileManagementController::class, 'destroy'])->name('admin.files.destroy');
    Route::get('/admin/files/{file}/download', [FileManagementController::class, 'download'])->name('admin.files.download');
    Route::post('/admin/files/bulk-delete', [FileManagementController::class, 'bulkDelete'])->name('admin.files.bulkDelete');
});

// Debug endpoint
Route::get('/debug/info', function () {
    return response()->json([
        'app_env' => env('APP_ENV'),
        'app_debug' => env('APP_DEBUG'),
        'database' => [
            'connection' => DB::connection()->getName(),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
        ],
        'tables' => DB::select('SHOW TABLES'),
        'users_count' => DB::table('users')->count(),
    ]);
});

require __DIR__.'/auth.php';
