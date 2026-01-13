<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureDatabaseMigrated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if migrations table exists
            $hasMigrationsTable = DB::connection()->getSchemaBuilder()->hasTable('migrations');
            
            if (!$hasMigrationsTable) {
                // Run migrations
                Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Exception $e) {
            // Log but don't fail - let the request continue
            \Log::warning('Migration check failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}
