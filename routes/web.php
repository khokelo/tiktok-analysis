<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/', function () {
    return redirect('/login');
});

require __DIR__.'/auth.php';
