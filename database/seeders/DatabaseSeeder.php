<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
                ['name' => 'Devices', 'slug' => 'devices', 'icon' => 'battery', 'color' => 'blue', 'is_active' => true],
                ['name' => 'E-Liquids', 'slug' => 'e-liquids', 'icon' => 'droplet', 'color' => 'green', 'is_active' => true],
                ['name' => 'Coils & Pods', 'slug' => 'coils-pods', 'icon' => 'settings', 'color' => 'orange', 'is_active' => true],
                ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => 'tool', 'color' => 'purple', 'is_active' => true],
            ] as $category) {
                Category::updateOrCreate(['slug' => $category['slug']], $category);
            }
        }

        foreach ($this->products() as $product) {
            $flavors = $this->flavorRowsFor($product);
            $product['slug'] = Str::slug($product['name']);

            if (Schema::hasTable('categories') && Schema::hasColumn('products', 'category_id')) {
                $product['category_id'] = Category::where('name', $product['category'])->value('id');
            }

            $productModel = Product::updateOrCreate(
                ['name' => $product['name']],
                $this->onlyExistingColumns('products', $product)
            );

            if (Schema::hasTable('product_flavors')) {
                $flavorNames = [];

                foreach ($flavors as $flavor) {
                    $flavorNames[] = $flavor['name'];

                    $productModel->flavors()->updateOrCreate(
                        ['name' => $flavor['name']],
                        [
                            'stock' => $flavor['stock'],
                            'reorder_level' => $flavor['reorder_level'],
                            'option_type' => $flavor['option_type'] ?? 'flavor',
                            'is_active' => true,
                        ]
                    );
                }

                $productModel->flavors()->whereNotIn('name', $flavorNames)->delete();

                if (Schema::hasColumn('products', 'flavor')) {
                    $productModel->forceFill(['flavor' => implode(', ', $flavorNames)])->save();
                }

                $productModel->syncStockFromFlavors();
            }
        }

        if (class_exists(ProductSeeder::class)) {
            $this->call(ProductSeeder::class);
        } elseif (class_exists(SharedProductsSeeder::class)) {
            $this->call(SharedProductsSeeder::class);
        }

        if (Schema::hasTable('promo_codes')) {
            PromoCode::updateOrCreate(
                ['code' => 'PUFF10'],
                [
                    'type' => 'percent',
                    'value' => 10,
                    'minimum_order_amount' => 1000,
                    'usage_limit' => 200,
                    'is_active' => true,
                ]
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
                'tags' => ['pod', 'refillable', 'usb-c'],
                'description' => 'Compact refillable pod device with USB-C charging.',
            ],
            [
                'name' => 'Naked 100 Lava Flow 60ml',
                'sku' => 'NK-LF-3MG',
                'category' => 'E-Liquids',
                'brand' => 'Naked 100',
                'product_type' => Product::TYPE_E_LIQUID,
                'nicotine_type' => 'freebase',
                'nicotine_strengths' => ['3'],
                'volume_ml' => 60,
                'price' => 450,
                'stock' => 12,
                'reorder_level' => 8,
                'badge' => 'hot',
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.6,
                'tags' => ['e-liquid', 'fruit', 'strawberry', 'pineapple'],
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
                'tags' => ['coil', 'replacement', 'smok'],
                'description' => 'Replacement coil for RPM 5 compatible devices.',
            ],
            [
                'name' => 'Uwell Caliburn A3S Pod Kit',
                'sku' => 'UW-A3S-SLV',
                'category' => 'Devices',
                'brand' => 'Uwell',
                'price' => 1650,
                'original_price' => 1890,
                'stock' => 20,
                'reorder_level' => 8,
                'badge' => 'sale',
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.7,
                'tags' => ['pod', 'starter', 'refillable'],
                'description' => 'Slim pod system with reliable flavor output and simple operation.',
            ],
            [
                'name' => 'Voopoo Drag S Pro Kit',
                'sku' => 'VP-DRAGSP-BLK',
                'category' => 'Devices',
                'brand' => 'Voopoo',
                'price' => 2100,
                'original_price' => 2450,
                'stock' => 9,
                'reorder_level' => 6,
                'badge' => 'hot',
                'is_featured' => true,
                'is_active' => true,
                'rating' => 4.9,
                'tags' => ['mod', 'pod-mod', 'adjustable'],
                'description' => 'High-performance pod mod with adjustable airflow and power.',
            ],
            [
                'name' => 'Geekvape Wenax Q Mini',
                'sku' => 'GV-WQ-MINI',
                'category' => 'Devices',
                'brand' => 'Geekvape',
                'price' => 1190,
                'original_price' => 1390,
                'stock' => 31,
                'reorder_level' => 10,
                'badge' => 'new',
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.5,
                'tags' => ['pod', 'compact', 'starter'],
                'description' => 'Pocket-friendly pod kit with smooth draw activation.',
            ],
            [
                'name' => 'Ripe Vapes VCT 60ml',
                'sku' => 'RV-VCT-60',
                'category' => 'E-Liquids',
                'brand' => 'Ripe Vapes',
                'product_type' => Product::TYPE_E_LIQUID,
                'nicotine_type' => 'freebase',
                'nicotine_strengths' => ['3', '6'],
                'volume_ml' => 60,
                'price' => 520,
                'stock' => 18,
                'reorder_level' => 8,
                'badge' => 'none',
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.4,
                'tags' => ['e-liquid', 'dessert', 'vanilla'],
                'description' => 'Creamy custard tobacco profile for adult customers.',
            ],
            [
                'name' => 'Tokyo Salt Lychee Ice 30ml',
                'sku' => 'TK-LYC-ICE',
                'category' => 'E-Liquids',
                'brand' => 'Tokyo',
                'product_type' => Product::TYPE_E_LIQUID,
                'nicotine_type' => 'saltnic',
                'nicotine_strengths' => ['30'],
                'volume_ml' => 30,
                'price' => 390,
                'stock' => 26,
                'reorder_level' => 10,
                'badge' => 'new',
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.2,
                'tags' => ['e-liquid', 'salt-nic', 'lychee', 'ice'],
                'description' => 'Lychee menthol salt nicotine blend for compatible low-wattage devices.',
            ],
            [
                'name' => 'Oxva Xlim V2 Replacement Pod',
                'sku' => 'OX-XLIM-V2POD',
                'category' => 'Coils & Pods',
                'brand' => 'Oxva',
                'price' => 220,
                'stock' => 5,
                'reorder_level' => 10,
                'badge' => 'none',
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.6,
                'tags' => ['pod', 'replacement', 'oxva'],
                'description' => 'Replacement pod cartridge for compatible Oxva Xlim devices.',
            ],
            [
                'name' => 'Vaporesso XROS Replacement Pod 0.8 Ohm',
                'sku' => 'VP-XROS-POD08',
                'category' => 'Coils & Pods',
                'brand' => 'Vaporesso',
                'price' => 240,
                'stock' => 7,
                'reorder_level' => 12,
                'badge' => 'hot',
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.8,
                'tags' => ['pod', 'replacement', 'xros'],
                'description' => 'Refillable replacement pod for XROS series devices.',
            ],
            [
                'name' => 'Type-C Fast Charging Cable',
                'sku' => 'ACC-USBC-1M',
                'category' => 'Accessories',
                'brand' => 'Puffcart',
                'price' => 149,
                'stock' => 40,
                'reorder_level' => 15,
                'badge' => 'none',
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.1,
                'tags' => ['accessory', 'charger', 'usb-c'],
                'description' => 'Durable USB-C cable for compatible devices.',
            ],
            [
                'name' => 'Travel Vape Case',
                'sku' => 'ACC-CASE-BLK',
                'category' => 'Accessories',
                'brand' => 'Puffcart',
                'price' => 299,
                'stock' => 14,
                'reorder_level' => 8,
                'badge' => 'sale',
                'is_featured' => false,
                'is_active' => true,
                'rating' => 4.0,
                'tags' => ['accessory', 'case', 'storage'],
                'description' => 'Compact travel case for carrying devices, pods, and small accessories.',
            ],
        ];
    }

    private function onlyExistingColumns(string $table, array $data): array
    {
        return collect($data)
            ->filter(fn ($value, string $column) => Schema::hasColumn($table, $column))
            ->all();
    }

    private function flavorRowsFor(array $product): array
    {
        $stock = max(0, (int) ($product['stock'] ?? 0));
        $reorderLevel = max(0, (int) ($product['reorder_level'] ?? 5));
        $names = match ($product['category'] ?? '') {
            'Devices' => ['Black', 'Silver', 'Blue'],
            'E-Liquids' => ['Original', 'Mango Ice', 'Grape Ice'],
            'Coils & Pods' => ['Mint', 'Mango', 'Tobacco'],
            'Accessories' => ['Black', 'Blue'],
            default => ['Original'],
        };

        $baseStock = intdiv($stock, count($names));
        $remainder = $stock % count($names);

        return collect($names)
            ->map(fn (string $name, int $index) => [
                'name' => $name,
                'stock' => $baseStock + ($index < $remainder ? 1 : 0),
                'reorder_level' => $reorderLevel,
            ])
            ->all();
    }
}
