<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RegisterRoutes
{
    public function handle(Request $request, Closure $next)
    {
        // Register dynamic test routes before processing request
        if (!Route::has('test')) {
            Route::get('/dynamic-test', function () {
                return response()->json(['status' => 'ok', 'source' => 'middleware']);
            });
        }
        
        return $next($request);
    }
}
