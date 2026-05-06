<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Only create if admin doesn't exist
        if (!User::where('role', 'admin')->where('email', 'admin@puffcart.local')->exists()) {
            User::create([
                'name' => 'Puffcart Admin',
                'email' => 'admin@puffcart.local',
                'username' => 'admin',
                'password' => Hash::make('admin123'), // This is HASHED - only for local development
                'role' => 'admin',
                'is_active' => true,
                'age_verified' => true,
                'age_confirmed' => true,
                'privacy_consent' => true,
                'verification_status' => 'approved',
                'mfa_enabled' => false, // Set to true for production
            ]);

            \Illuminate\Support\Facades\Log::info('Admin user seeded with hashed password. Remember: admin123 is ONLY for local development!');
        }
    }
}
