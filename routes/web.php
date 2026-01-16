<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FileController as AdminFileController;
use App\Http\Controllers\Admin\SaleController as AdminSaleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['status' => 'ok', 'message' => 'Laravel 11 is running!']);
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/upload-csv', [DashboardController::class, 'upload'])->name('upload.csv');
});

Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales', [AdminSaleController::class, 'index'])->name('sales.index');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/files', [AdminFileController::class, 'index'])->name('files.index');
    Route::get('/files/{file}', [AdminFileController::class, 'show'])->name('files.show');
});
