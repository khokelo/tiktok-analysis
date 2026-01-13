<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseDiagnostics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:diagnose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose database connection and migrations status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('🔍 Database Diagnostics');
        $this->line('======================');
        $this->newLine();

        // Check environment variables
        $this->line('📋 Environment Variables:');
        $this->line('DB_CONNECTION: ' . env('DB_CONNECTION'));
        $this->line('DB_HOST: ' . env('DB_HOST'));
        $this->line('DB_PORT: ' . env('DB_PORT'));
        $this->line('DB_DATABASE: ' . env('DB_DATABASE'));
        $this->line('DB_USERNAME: ' . env('DB_USERNAME'));
        $this->line('DB_PASSWORD: ' . (env('DB_PASSWORD') ? '***SET***' : '***NOT SET***'));
        $this->newLine();

        // Test connection
        $this->line('🔗 Testing Connection:');
        try {
            DB::connection()->getPdo();
            $this->line('✅ Connection successful!');
        } catch (\Exception $e) {
            $this->error('❌ Connection failed: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Check migrations table
        $this->line('📊 Checking Migrations:');
        try {
            $migrations = DB::table('migrations')->get();
            $this->line('✅ Migrations table exists');
            $this->line('Migrated files: ' . $migrations->count());
            
            if ($migrations->count() > 0) {
                foreach ($migrations as $migration) {
                    $this->line('  - ' . $migration->migration);
                }
            } else {
                $this->warn('⚠️  No migrations recorded. Need to run: php artisan migrate --force');
            }
        } catch (\Exception $e) {
            $this->error('❌ Migrations table not found: ' . $e->getMessage());
            $this->warn('⚠️  Need to run migrations first: php artisan migrate --force');
            return 1;
        }
        $this->newLine();

        // Check tables
        $this->line('📋 Checking Tables:');
        try {
            $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?", [env('DB_DATABASE')]);
            $this->line('✅ Found ' . count($tables) . ' tables:');
            
            $expectedTables = [
                'users' => '👤 Users',
                'sessions' => '🔐 Sessions',
                'cache' => '💾 Cache',
                'jobs' => '⚙️  Jobs',
                'migrations' => '📝 Migrations',
                'sales' => '💰 Sales',
                'tiktok_sales' => '🎵 TikTok Sales',
                'uploaded_files' => '📁 Uploaded Files',
            ];

            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;
                $icon = isset($expectedTables[$tableName]) ? $expectedTables[$tableName] : '📄 ' . $tableName;
                $this->line('  ✅ ' . $icon);
            }

            // Check for missing tables
            $missing = [];
            foreach ($expectedTables as $table => $label) {
                if (!collect($tables)->where('TABLE_NAME', $table)->count()) {
                    $missing[] = $table;
                }
            }

            if (!empty($missing)) {
                $this->warn('⚠️  Missing tables: ' . implode(', ', $missing));
                $this->warn('Run migrations: php artisan migrate --force');
            }

        } catch (\Exception $e) {
            $this->error('❌ Cannot check tables: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->line('✅ Diagnostics complete!');
        return 0;
    }
}
