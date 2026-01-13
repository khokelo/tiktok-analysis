<?php

use Illuminate\Support\Facades\Route;

// Minimal test routes
Route::get('/test', function () {
    return response()->json(['status' => 'ok', 'time' => now()]);
});

Route::get('/', function () {
    return redirect('/login');
});

// require __DIR__.'/auth.php';
