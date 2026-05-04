<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::create([
            'name'         => 'PuffCart Admin',
            'email'        => 'admin@puffcart.ph',
            'password'     => Hash::make('password'),
            'role'         => 'admin',
            'age_verified' => true,
        ]);

        // Demo customer
        User::create([
            'name'         => 'Juan dela Cruz',
            'email'        => 'juan@example.com',
            'password'     => Hash::make('password'),
            'role'         => 'customer',
            'phone'        => '09171234567',
            'address'      => '123 Sample St., Pasig City, Metro Manila',
            'age_verified' => true,
        ]);

        // Categories
        $categories = [
            ['name' => 'Devices',      'slug' => 'devices',      'color' => 'cyan'],
            ['name' => 'E-Liquids',    'slug' => 'e-liquids',    'color' => 'pink'],
            ['name' => 'Coils & Pods', 'slug' => 'coils-pods',   'color' => 'yellow'],
            ['name' => 'Accessories',  'slug' => 'accessories',  'color' => 'purple'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Sample products
        $products = [
            ['name' => 'Vaporesso XROS 4 Mini', 'brand' => 'Vaporesso', 'category_id' => 1,
             'sku' => 'VP-X4M-BLK', 'price' => 1299, 'original_price' => 1600,
             'stock' => 48, 'badge' => 'new', 'is_featured' => true, 'rating' => 4.8,
             'specs' => ['Battery' => '1000mAh', 'Wattage' => '11W', 'Capacity' => '2ml', 'Charging' => 'USB-C']],

            ['name' => 'Voopoo Drag S Pro', 'brand' => 'Voopoo', 'category_id' => 1,
             'sku' => 'VP-DSP-GRY', 'price' => 2100, 'original_price' => 2500,
             'stock' => 24, 'badge' => 'hot', 'is_featured' => true, 'rating' => 4.7,
             'specs' => ['Battery' => '2500mAh', 'Wattage' => '80W', 'Capacity' => '4.5ml', 'Charging' => 'USB-C']],

            ['name' => 'Naked 100 Lava Flow 60ml', 'brand' => 'Naked 100', 'category_id' => 2,
             'sku' => 'NK-LF-3MG', 'price' => 450, 'original_price' => null,
             'stock' => 12, 'badge' => 'hot', 'is_featured' => true, 'rating' => 4.6],

            ['name' => 'Smok RPM 5 Coil 0.2Ω', 'brand' => 'Smok', 'category_id' => 3,
             'sku' => 'SK-RPM5-02', 'price' => 299, 'original_price' => null,
             'stock' => 4, 'badge' => 'none', 'is_featured' => false, 'rating' => 4.3],

            ['name' => 'Uwell Caliburn A3S', 'brand' => 'Uwell', 'category_id' => 1,
             'sku' => 'UW-A3S-BLK', 'price' => 1650, 'original_price' => 1900,
             'stock' => 30, 'badge' => 'new', 'is_featured' => true, 'rating' => 4.5],

            ['name' => 'Voopoo PnP-VM6 Coil', 'brand' => 'Voopoo', 'category_id' => 3,
             'sku' => 'VP-PNP-VM6', 'price' => 199, 'original_price' => null,
             'stock' => 9, 'badge' => 'none', 'is_featured' => false, 'rating' => 4.2],
        ];

        foreach ($products as $prod) {
            $prod['slug']        = Str::slug($prod['name']);
            $prod['description'] = "High-quality {$prod['name']} from {$prod['brand']}. Perfect for both beginners and experienced vapers.";
            $prod['reorder_level'] = 10;
            Product::create($prod);
        }
    }
}
