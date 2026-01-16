<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['status' => 'ok', 'message' => 'Laravel 11 is running!']);
});

Route::get('/', function () {
    return view('welcome');
});
