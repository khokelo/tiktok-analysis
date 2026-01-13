<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user - Force update if exists
        $admin = User::where('email', 'admin@email.com')->first();
        if ($admin) {
            $admin->update([
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        } else {
            User::create([
                'email' => 'admin@email.com',
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }

        // Create sample users
        User::firstOrCreate(
            ['email' => 'user1@email.com'],
            [
                'name' => 'User One',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user2@email.com'],
            [
                'name' => 'User Two',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );
    }
}
