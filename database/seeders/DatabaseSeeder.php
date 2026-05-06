<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@puffcart.local'],
            $this->onlyExistingColumns('users', [
                'name' => 'Puffcart Admin',
                'username' => 'admin',
                'email' => 'admin@puffcart.local',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'age_verified' => true,
                'age_confirmed' => true,
                'privacy_consent' => true,
                'verification_status' => 'approved',
                'verification_reviewed_at' => now(),
                'is_active' => true,
            ])
        );

        User::updateOrCreate(
            ['email' => 'juan@example.com'],
            $this->onlyExistingColumns('users', [
                'name' => 'Juan dela Cruz',
                'username' => 'juan',
                'email' => 'juan@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '09171234567',
                'address' => '123 Sample St., Pasig City, Metro Manila',
                'age_verified' => true,
                'age_confirmed' => true,
                'privacy_consent' => true,
                'verification_status' => 'approved',
                'verification_reviewed_at' => now(),
                'is_active' => true,
            ])
        );

        if (Schema::hasTable('categories')) {
            foreach ([
                ['name' => 'Devices', 'slug' => 'devices', 'color' => 'blue'],
                ['name' => 'E-Liquids', 'slug' => 'e-liquids', 'color' => 'green'],
                ['name' => 'Coils & Pods', 'slug' => 'coils-pods', 'color' => 'orange'],
                ['name' => 'Accessories', 'slug' => 'accessories', 'color' => 'purple'],
            ] as $category) {
                Category::updateOrCreate(['slug' => $category['slug']], $category);
            }
        }

        foreach ($this->products() as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $this->onlyExistingColumns('products', $product)
            );
        }
    }

    private function products(): array
    {
        return [
            [
                'name' => 'Vaporesso XROS 4 Mini',
                'sku' => 'VP-X4M-BLK',
                'category' => 'Devices',
                'brand' => 'Vaporesso',
                'price' => 1299,
                'original_price' => 1600,
                'stock' => 48,
                'reorder_level' => 10,
                'badge' => 'new',
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.8,
                'description' => 'Compact refillable pod device with USB-C charging.',
            ],
            [
                'name' => 'Naked 100 Lava Flow 60ml',
                'sku' => 'NK-LF-3MG',
                'category' => 'E-Liquids',
                'brand' => 'Naked 100',
                'price' => 450,
                'stock' => 12,
                'reorder_level' => 8,
                'badge' => 'hot',
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.6,
                'description' => 'Fruit-forward e-liquid for adult customers.',
            ],
            [
                'name' => 'Smok RPM 5 Coil 0.2 Ohm',
                'sku' => 'SK-RPM5-02',
                'category' => 'Coils & Pods',
                'brand' => 'Smok',
                'price' => 299,
                'stock' => 4,
                'reorder_level' => 6,
                'badge' => 'none',
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.3,
                'description' => 'Replacement coil for RPM 5 compatible devices.',
            ],
        ];
    }

    private function onlyExistingColumns(string $table, array $data): array
    {
        return collect($data)
            ->filter(fn ($value, string $column) => Schema::hasColumn($table, $column))
            ->all();
    }
}
