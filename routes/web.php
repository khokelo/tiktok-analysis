<?php

\Illuminate\Support\Facades\Route::get('/test', function () {
    return response()->json(['status' => 'ok']);
});

\Illuminate\Support\Facades\Route::get('/', function () {
    return redirect('/login');
});
