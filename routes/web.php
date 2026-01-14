<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['status' => 'ok', 'message' => 'Laravel 12 is running!']);
});

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
