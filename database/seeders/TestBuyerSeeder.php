<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestBuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'testbuyer@example.com'],
            [
                'name' => 'Test Buyer',
                'username' => 'testbuyer',
                'email' => 'testbuyer@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_active' => true,
                'age_verified' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
