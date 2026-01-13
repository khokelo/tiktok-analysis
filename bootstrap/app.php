<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureDatabaseMigrated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Ensure cache is clear for route changes
@unlink(base_path('bootstrap/cache/routes-v7.php'));
@unlink(base_path('bootstrap/cache/config.php'));

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Temporarily disabled - causing issues
        // $middleware->append(EnsureDatabaseMigrated::class);
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
