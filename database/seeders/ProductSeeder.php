<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->products() as $item) {
            $data = $this->onlyExistingColumns('products', $item['data']);

            if (Schema::hasTable('categories') && Schema::hasColumn('products', 'category_id')) {
                $categoryName = $data['category'] ?? null;

                if ($categoryName) {
                    $category = Category::firstOrCreate(
                        ['slug' => Str::slug($categoryName)],
                        ['name' => $categoryName, 'is_active' => true]
                    );

                    $data['category_id'] = $category->id;
                }
            }

            $product = Product::updateOrCreate($item['attributes'], $data);

            if (Schema::hasTable('product_flavors')) {
                $names = [];

                foreach ($item['flavors'] as $flavor) {
                    $flavor = $this->onlyExistingColumns('product_flavors', $flavor);

                    if (empty($flavor['name'])) {
                        continue;
                    }

                    $names[] = $flavor['name'];
                    $lookup = ['name' => $flavor['name']];

                    if (Schema::hasColumn('product_flavors', 'option_type') && isset($flavor['option_type'])) {
                        $lookup['option_type'] = $flavor['option_type'];
                    }

                    $product->flavors()->updateOrCreate(
                        $lookup,
                        $flavor
                    );
                }

                if ($names !== []) {
                    $product->flavors()->whereNotIn('name', $names)->delete();
                    $product->syncStockFromFlavors();
                }
            }
        }
    }

    private function onlyExistingColumns(string $table, array $data): array
    {
        return collect($data)
            ->filter(fn ($value, string $column) => Schema::hasColumn($table, $column))
            ->all();
    }

    private function products(): array
    {
        return [
  0 => 
  [
    'attributes' => 
    [
      'name' => 'Naked 100 Lava Flow 60ml',
    ],
    'data' => 
    [
      'name' => 'Naked 100 Lava Flow 60ml',
      'category' => 'E-Liquids',
      'brand' => 'Naked 100',
      'price' => 450,
      'description' => 'Fruit-forward e-liquid for adult customers.',
      'stock' => 48,
      'sku' => 'NK-LF-3MG',
      'original_price' => NULL,
      'image' => 'products/25rmh8cilr6H6Bucznhgqh1fKvJrZGxhtCehmalj.jpg',
      'badge' => 'hot',
      'reorder_level' => 8,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => 4.6,
      'slug' => 'naked-100-lava-flow-60ml',
      'tags' => 
      [
        0 => 'e-liquid',
        1 => 'fruit',
        2 => 'strawberry',
        3 => 'pineapple',
      ],
      'sales_count' => 2,
      'views_count' => 3,
      'product_type' => 'e_liquid',
      'flavor' => 'Grape Ice, Mango Ice, Original',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '8',
      ],
      'volume_ml' => 60,
      'product_name' => 'Naked 100 Lava Flow 60ml',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Grape Ice',
        'stock' => 9,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Grape Ice',
      ],
      1 => 
      [
        'name' => 'Mango Ice',
        'stock' => 18,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Mango Ice',
      ],
      2 => 
      [
        'name' => 'Original',
        'stock' => 21,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Original',
      ],
    ],
  ],
  1 => 
  [
    'attributes' => 
    [
      'name' => 'Uwell Caliburn A3S Pod Kit',
    ],
    'data' => 
    [
      'name' => 'Uwell Caliburn A3S Pod Kit',
      'category' => 'Devices',
      'brand' => 'Uwell',
      'price' => 1650,
      'description' => 'Slim pod system with reliable flavor output and simple operation.',
      'stock' => 70,
      'sku' => 'UW-A3S-SLV',
      'original_price' => 1890,
      'image' => 'products/UfshI4neA96MvwxRoyzSqJPDUR0QU977YvPSlvrL.webp',
      'badge' => 'sale',
      'reorder_level' => 8,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => 4.7,
      'slug' => 'uwell-caliburn-a3s-pod-kit',
      'tags' => 
      [
        0 => 'pod',
        1 => 'starter',
        2 => 'refillable',
      ],
      'sales_count' => 1,
      'views_count' => 8,
      'product_type' => 'other',
      'flavor' => 'Moonlight Silver, Space Gray, Midnight Black, Lake Green, Iris Purple, Ocean Flame',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'Uwell Caliburn A3S Pod Kit',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Iris Purple',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Iris Purple',
      ],
      1 => 
      [
        'name' => 'Lake Green',
        'stock' => 22,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Lake Green',
      ],
      2 => 
      [
        'name' => 'Midnight Black',
        'stock' => 18,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Midnight Black',
      ],
      3 => 
      [
        'name' => 'Moonlight Silver',
        'stock' => 7,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Moonlight Silver',
      ],
      4 => 
      [
        'name' => 'Ocean Flame',
        'stock' => 9,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Ocean Flame',
      ],
      5 => 
      [
        'name' => 'Space Gray',
        'stock' => 12,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Space Gray',
      ],
    ],
  ],
  2 => 
  [
    'attributes' => 
    [
      'name' => 'Geekvape Wenax Q Mini',
    ],
    'data' => 
    [
      'name' => 'Geekvape Wenax Q Mini',
      'category' => 'Devices',
      'brand' => 'Geekvape',
      'price' => 1190,
      'description' => 'Pocket-friendly pod kit with smooth draw activation.',
      'stock' => 39,
      'sku' => 'GV-WQ-MINI',
      'original_price' => 1390,
      'image' => 'products/8oq4J6P6ERc0XsZ2RYtOgDRbUSDHdpM9vvLcVz91.jpg',
      'badge' => 'new',
      'reorder_level' => 10,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 4.5,
      'slug' => 'geekvape-wenax-q-mini',
      'tags' => 
      [
        0 => 'pod',
        1 => 'compact',
        2 => 'starter',
      ],
      'sales_count' => 0,
      'views_count' => 2,
      'product_type' => 'other',
      'flavor' => 'Black, Blue, Silver',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'Geekvape Wenax Q Mini',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'stock' => 11,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Black',
      ],
      1 => 
      [
        'name' => 'Blue',
        'stock' => 11,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Blue',
      ],
      2 => 
      [
        'name' => 'Silver',
        'stock' => 17,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Silver',
      ],
    ],
  ],
  3 => 
  [
    'attributes' => 
    [
      'name' => 'Ripe Vapes VCT ',
    ],
    'data' => 
    [
      'name' => 'Ripe Vapes VCT ',
      'category' => 'E-Liquids',
      'brand' => 'Ripe Vapes',
      'price' => 520,
      'description' => 'Creamy custard tobacco profile for adult customers.',
      'stock' => 40,
      'sku' => 'RV-VCT-60',
      'original_price' => NULL,
      'image' => 'products/LWzOzvfedZ0An9LLGiJMEsk7UyjHN4i3vzEMECRj.jpg',
      'badge' => 'none',
      'reorder_level' => 8,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 4.4,
      'slug' => 'ripe-vapes-vct',
      'tags' => 
      [
        0 => 'e-liquid',
        1 => 'dessert',
        2 => 'vanilla',
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'e_liquid',
      'flavor' => 'Grape Ice, Mango Ice, Original',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '8',
      ],
      'volume_ml' => 60,
      'product_name' => 'Ripe Vapes VCT ',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Grape Ice',
        'stock' => 13,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Grape Ice',
      ],
      1 => 
      [
        'name' => 'Mango Ice',
        'stock' => 21,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Mango Ice',
      ],
      2 => 
      [
        'name' => 'Original',
        'stock' => 6,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Original',
      ],
    ],
  ],
  4 => 
  [
    'attributes' => 
    [
      'name' => 'Tokyo Salt Lychee Ice ',
    ],
    'data' => 
    [
      'name' => 'Tokyo Salt Lychee Ice ',
      'category' => 'E-Liquids',
      'brand' => 'Tokyo',
      'price' => 390,
      'description' => 'Lychee menthol salt nicotine blend for compatible low-wattage devices.',
      'stock' => 26,
      'sku' => 'TK-LYC-ICE',
      'original_price' => NULL,
      'image' => 'products/21eApga7ZAQoKlO9R3zT5tRtryszWW04jbn56g8Z.webp',
      'badge' => 'new',
      'reorder_level' => 10,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 4.2,
      'slug' => 'tokyo-salt-lychee-ice',
      'tags' => 
      [
        0 => 'e-liquid',
        1 => 'salt-nic',
        2 => 'lychee',
        3 => 'ice',
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'e_liquid',
      'flavor' => 'Grape Ice, Mango Ice, Original',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => 'saltnic',
      'nicotine_strengths' => 
      [
        0 => '30',
      ],
      'volume_ml' => 30,
      'product_name' => 'Tokyo Salt Lychee Ice ',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Grape Ice',
        'stock' => 8,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Grape Ice',
      ],
      1 => 
      [
        'name' => 'Mango Ice',
        'stock' => 9,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Mango Ice',
      ],
      2 => 
      [
        'name' => 'Original',
        'stock' => 9,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Original',
      ],
    ],
  ],
  5 => 
  [
    'attributes' => 
    [
      'name' => 'Oxva Xlim V2 Replacement Pod',
    ],
    'data' => 
    [
      'name' => 'Oxva Xlim V2 Replacement Pod',
      'category' => 'Coils & Pods',
      'brand' => 'Oxva',
      'price' => 220,
      'description' => 'Replacement pod cartridge for compatible Oxva Xlim devices.',
      'stock' => 84,
      'sku' => 'OX-XLIM-V2POD',
      'original_price' => NULL,
      'image' => 'products/bRI6Yab44MWlYZLcxOVXqfFNWb3sfgEye9jrzyPR.webp',
      'badge' => 'none',
      'reorder_level' => 10,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 4.6,
      'slug' => 'oxva-xlim-v2-replacement-pod',
      'tags' => 
      [
        0 => 'pod',
        1 => 'replacement',
        2 => 'oxva',
      ],
      'sales_count' => 0,
      'views_count' => 1,
      'product_type' => 'other',
      'flavor' => '0.8, 0.6, 1.2',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'Oxva Xlim V2 Replacement Pod',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => '0.6',
        'stock' => 25,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => '0.6',
      ],
      1 => 
      [
        'name' => '0.8',
        'stock' => 30,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => '0.8',
      ],
      2 => 
      [
        'name' => '1.2',
        'stock' => 29,
        'reorder_level' => 10,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => '1.2',
      ],
    ],
  ],
  6 => 
  [
    'attributes' => 
    [
      'name' => 'Vaporesso XROS Replacement Pod ',
    ],
    'data' => 
    [
      'name' => 'Vaporesso XROS Replacement Pod ',
      'category' => 'Coils & Pods',
      'brand' => 'Vaporesso',
      'price' => 240,
      'description' => 'Refillable replacement pod for XROS series devices.',
      'stock' => 23,
      'sku' => 'VP-XROS-POD08',
      'original_price' => NULL,
      'image' => 'products/FHX8azD0x8Y9PqSqOWpSW5UCY75eESBbuewEqswF.webp',
      'badge' => 'hot',
      'reorder_level' => 12,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 4.8,
      'slug' => 'vaporesso-xros-replacement-pod',
      'tags' => 
      [
        0 => 'pod',
        1 => 'replacement',
        2 => 'xros',
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'other',
      'flavor' => 'Mango, 0.8, 0.6',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'Vaporesso XROS Replacement Pod ',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => '0.6',
        'stock' => 18,
        'reorder_level' => 12,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => '0.6',
      ],
      1 => 
      [
        'name' => '0.8',
        'stock' => 3,
        'reorder_level' => 12,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => '0.8',
      ],
      2 => 
      [
        'name' => 'Mango',
        'stock' => 2,
        'reorder_level' => 12,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Mango',
      ],
    ],
  ],
  7 => 
  [
    'attributes' => 
    [
      'name' => 'Travel Vape Case',
    ],
    'data' => 
    [
      'name' => 'Travel Vape Case',
      'category' => 'Accessories',
      'brand' => 'Puffcart',
      'price' => 300,
      'description' => 'Compact travel case for carrying devices, pods, and small accessories.',
      'stock' => 17,
      'sku' => 'ACC-CASE-BLK',
      'original_price' => 400,
      'image' => 'products/MqKdKytb2IVKMYiZTX0gJVrEOYdITHeq8eeK0bJN.jpg',
      'badge' => 'sale',
      'reorder_level' => 8,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 4,
      'slug' => 'travel-vape-case',
      'tags' => 
      [
        0 => 'accessory',
        1 => 'case',
        2 => 'storage',
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'other',
      'flavor' => 'Black, Blue',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'Travel Vape Case',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'stock' => 10,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Black',
      ],
      1 => 
      [
        'name' => 'Blue',
        'stock' => 7,
        'reorder_level' => 8,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Blue',
      ],
    ],
  ],
  8 => 
  [
    'attributes' => 
    [
      'name' => 'CHILLAX INFINITE KIT ( POD & BATTERY)',
    ],
    'data' => 
    [
      'name' => 'CHILLAX INFINITE KIT ( POD & BATTERY)',
      'category' => 'Devices',
      'brand' => 'CHILLAX',
      'price' => 650,
      'description' => 'CHILLAX INFINITE KIT (POD & BATTERY) is a sleek and powerful pod vape device designed for smooth flavor delivery and long-lasting performance. Featuring an ultra-thin body, dual mesh coil technology, and a clear e-liquid viewing pod, it delivers rich flavor and satisfying vapor from the first puff to the last. The device includes a rechargeable 600mAh battery, Type-C charging, and up to 25,000 puffs for extended use. Its compact and stylish design makes it perfect for everyday vaping convenience and portability.',
      'stock' => 24,
      'sku' => 'CHLX',
      'original_price' => NULL,
      'image' => 'products/BjK6QHmekdl9vV4DehROyydLeshsKVJFshL4lx20.jpg',
      'badge' => 'new',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'chillax-infinite-kit-pod-battery',
      'tags' => 
      [
        0 => 'Disposable pods',
      ],
      'sales_count' => 0,
      'views_count' => 25,
      'product_type' => 'bundle',
      'flavor' => 'COSMIC CRUSH - GRAPES, CRYSTAL WINK - BUBBLEGUM, HAPPY BUDDIES - YAKULT, IVORY SYMPHONY - LYCHEE, PINK HARMONY - JUICE STRAWBERRY, RUSTIC HAZE - CLASSIC TOBACCO, SILVER DYNASTY - MENTHOL ICE, SLICK KICK - SOUR APPLE, THUNDER BLAZED - GATORADE, TWILIG',
      'bundle_pods' => 'Any Flavors',
      'bundle_battery' => 'Battery/Device included',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'CHILLAX INFINITE KIT ( POD & BATTERY)',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'stock' => 24,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Black',
      ],
      1 => 
      [
        'name' => 'COSMIC CRUSH - GRAPES',
        'stock' => 19,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'COSMIC CRUSH - GRAPES',
      ],
      2 => 
      [
        'name' => 'CRYSTAL WINK - BUBBLEGUM',
        'stock' => 16,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'CRYSTAL WINK - BUBBLEGUM',
      ],
      3 => 
      [
        'name' => 'HAPPY BUDDIES - YAKULT',
        'stock' => 7,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'HAPPY BUDDIES - YAKULT',
      ],
      4 => 
      [
        'name' => 'IVORY SYMPHONY - LYCHEE',
        'stock' => 17,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'IVORY SYMPHONY - LYCHEE',
      ],
      5 => 
      [
        'name' => 'PINK HARMONY - JUICE STRAWBERRY',
        'stock' => 24,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'PINK HARMONY - JUICE STRAWBERRY',
      ],
      6 => 
      [
        'name' => 'RUSTIC HAZE - CLASSIC TOBACCO',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'RUSTIC HAZE - CLASSIC TOBACCO',
      ],
      7 => 
      [
        'name' => 'SILVER DYNASTY - MENTHOL ICE',
        'stock' => 1,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'SILVER DYNASTY - MENTHOL ICE',
      ],
      8 => 
      [
        'name' => 'SLICK KICK - SOUR APPLE',
        'stock' => 22,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'SLICK KICK - SOUR APPLE',
      ],
      9 => 
      [
        'name' => 'THUNDER BLAZED - GATORADE',
        'stock' => 1,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'THUNDER BLAZED - GATORADE',
      ],
      10 => 
      [
        'name' => 'TWILIGHT WILLOW - BLACKCURRANT',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'TWILIGHT WILLOW - BLACKCURRANT',
      ],
      11 => 
      [
        'name' => 'VERY MELLOW - WATERMELON',
        'stock' => 7,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'VERY MELLOW - WATERMELON',
      ],
      12 => 
      [
        'name' => 'VIOLET FUSION - TARO ICE CREAM',
        'stock' => 14,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'VIOLET FUSION - TARO ICE CREAM',
      ],
    ],
  ],
  9 => 
  [
    'attributes' => 
    [
      'name' => 'CHILLAX INFINITE  ( POD ONLY )',
    ],
    'data' => 
    [
      'name' => 'CHILLAX INFINITE  ( POD ONLY )',
      'category' => 'Devices',
      'brand' => 'CHILLAX',
      'price' => 450,
      'description' => 'CHILLAX INFINITE KIT (PODS ONLY) delivers a smooth, flavorful, and hassle-free vaping experience with its advanced dual mesh coil technology and leak-resistant pod design. Made for the Chillax Infinite device, these prefilled pods provide rich flavor, satisfying vapor production, and consistent performance from the first puff to the last. Its transparent pod design allows easy e-liquid visibility, while the compact and lightweight build makes it perfect for daily use and portability.',
      'stock' => 98,
      'sku' => 'CHLXPDS',
      'original_price' => 550,
      'image' => 'products/hkAL1TqGm3t9M4t2nxv15mzDIgRZFnW3WJ5hnzaL.jpg',
      'badge' => 'sale',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'chillax-infinite-pod-only',
      'tags' => 
      [
        0 => 'PODS',
      ],
      'sales_count' => 0,
      'views_count' => 12,
      'product_type' => 'pods',
      'flavor' => 'SLICK KICK - SOUR APPLE, COSMIC CRUSH - GRAPES, VERY MELLOW - WATERMELON, PINK HARMONY - JUICE STRAWBERRY, TWILIGHT WILLOW - BLACKCURRANT, SILVER DYNASTY - MENTHOL ICE, SILKY CLOUDS - VANILLA ICE CREAM, CRYSTAL WINK - BUBBLEGUM, MIDORI ZEN - MATCHA',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'CHILLAX INFINITE  ( POD ONLY )',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'COSMIC CRUSH - GRAPES',
        'stock' => 5,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'COSMIC CRUSH - GRAPES',
      ],
      1 => 
      [
        'name' => 'CRYSTAL WINK - BUBBLEGUM',
        'stock' => 8,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'CRYSTAL WINK - BUBBLEGUM',
      ],
      2 => 
      [
        'name' => 'MIDORI ZEN - MATCHA',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'MIDORI ZEN - MATCHA',
      ],
      3 => 
      [
        'name' => 'PINK HARMONY - JUICE STRAWBERRY',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'PINK HARMONY - JUICE STRAWBERRY',
      ],
      4 => 
      [
        'name' => 'SILKY CLOUDS - VANILLA ICE CREAM',
        'stock' => 13,
        'reorder_level' => 4,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'SILKY CLOUDS - VANILLA ICE CREAM',
      ],
      5 => 
      [
        'name' => 'SILVER DYNASTY - MENTHOL ICE',
        'stock' => 16,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'SILVER DYNASTY - MENTHOL ICE',
      ],
      6 => 
      [
        'name' => 'SLICK KICK - SOUR APPLE',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'SLICK KICK - SOUR APPLE',
      ],
      7 => 
      [
        'name' => 'TWILIGHT WILLOW - BLACKCURRANT',
        'stock' => 21,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'TWILIGHT WILLOW - BLACKCURRANT',
      ],
      8 => 
      [
        'name' => 'VERY MELLOW - WATERMELON',
        'stock' => 8,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'VERY MELLOW - WATERMELON',
      ],
    ],
  ],
  10 => 
  [
    'attributes' => 
    [
      'name' => 'RYUU BOOST 12000',
    ],
    'data' => 
    [
      'name' => 'RYUU BOOST 12000',
      'category' => 'Devices',
      'brand' => 'RYUU',
      'price' => 300,
      'description' => 'RYUU BOOST 12000 is a rechargeable pod vape that delivers smooth flavor, strong vapor, and up to 12,000 puffs. It features mesh coil technology, Type-C charging, and a sleek portable design for everyday vaping convenience.',
      'stock' => 53,
      'sku' => 'RYU',
      'original_price' => 0,
      'image' => 'products/4ZW2GaksC19yWPY4A4ay175WvDf0JC66kceoiVJ3.jpg',
      'badge' => 'new',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'ryuu-boost-12000',
      'tags' => 
      [
        0 => 'PODS',
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'pods',
      'flavor' => 'PASSION RED - STRAWBERRY, JITTER MUD - CARAMEL MACCHIATO, BLUE FREEZE - BLUEBERRY, BUBBLE BLOWER - BUBBLEGUM, CHEER BLAST - LYCHEE',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'RYUU BOOST 12000',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'BLUE FREEZE - BLUEBERRY',
        'stock' => 7,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BLUE FREEZE - BLUEBERRY',
      ],
      1 => 
      [
        'name' => 'BUBBLE BLOWER - BUBBLEGUM',
        'stock' => 8,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BUBBLE BLOWER - BUBBLEGUM',
      ],
      2 => 
      [
        'name' => 'CHEER BLAST - LYCHEE',
        'stock' => 10,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'CHEER BLAST - LYCHEE',
      ],
      3 => 
      [
        'name' => 'JITTER MUD - CARAMEL MACCHIATO',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'JITTER MUD - CARAMEL MACCHIATO',
      ],
      4 => 
      [
        'name' => 'PASSION RED - STRAWBERRY',
        'stock' => 16,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'PASSION RED - STRAWBERRY',
      ],
    ],
  ],
  11 => 
  [
    'attributes' => 
    [
      'name' => 'RYUU BOOST 12000 (Battery)',
    ],
    'data' => 
    [
      'name' => 'RYUU BOOST 12000 (Battery)',
      'category' => 'Devices',
      'brand' => 'RYUU',
      'price' => 350,
      'description' => 'RYUU BOOST 12000 BATTERY is a rechargeable vape device with a sleek and portable design, built for reliable performance and smooth power delivery. It features a long-lasting battery, Type-C charging, and compatibility with RYUU BOOST pods for convenient everyday use.',
      'stock' => 14,
      'sku' => 'RYUBT',
      'original_price' => NULL,
      'image' => 'products/Tf4Loxw5hY0Y1Ip0IlCIf9YiSLo6NZ57RwztoxKy.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'ryuu-boost-12000-battery',
      'tags' => 
      [
        0 => 'Battery',
      ],
      'sales_count' => 0,
      'views_count' => 2,
      'product_type' => 'battery',
      'flavor' => NULL,
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'RYUU BOOST 12000 (Battery)',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'stock' => 14,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Black',
      ],
    ],
  ],
  12 => 
  [
    'attributes' => 
    [
      'name' => 'KAIZER CLEAR',
    ],
    'data' => 
    [
      'name' => 'KAIZER CLEAR',
      'category' => 'Devices',
      'brand' => 'Kaizer',
      'price' => 430,
      'description' => 'KAIZER CLEAR is a sleek and rechargeable pod vape designed for smooth flavor delivery and reliable everyday performance. It features a transparent pod design, mesh coil technology, adjustable airflow, and a long-lasting battery for a clean and satisfying vaping experience.',
      'stock' => 24,
      'sku' => 'KZR',
      'original_price' => NULL,
      'image' => 'products/ls0mT4REERm7w3nCFF1f3KL3lYOY3xZWtR4Bviww.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'kaizer-clear',
      'tags' => 
      [
        0 => 'Pods',
      ],
      'sales_count' => 0,
      'views_count' => 9,
      'product_type' => 'pods',
      'flavor' => 'SHIROTA ICE - YAKULT  ',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'KAIZER CLEAR',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'SHIROTA ICE - YAKULT',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'SHIROTA ICE - YAKULT',
      ],
      1 => 
      [
        'name' => 'TOKYO JADE ICE - MATCHA',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'TOKYO JADE ICE - MATCHA',
      ],
    ],
  ],
  13 => 
  [
    'attributes' => 
    [
      'name' => 'KAIZER CLEAR (Battery)',
    ],
    'data' => 
    [
      'name' => 'KAIZER CLEAR (Battery)',
      'category' => 'Devices',
      'brand' => 'Kaizer',
      'price' => 350,
      'description' => 'KAIZER CLEAR BATTERY is a rechargeable vape device with a compact and stylish transparent design, built for smooth and reliable performance. It features a long-lasting battery, Type-C charging, and compatibility with Kaizer pods for convenient daily vaping.',
      'stock' => 20,
      'sku' => 'KZRBT',
      'original_price' => NULL,
      'image' => 'products/UrTASz6qm7eqhVGQa29uNrBXoRp4DZ9z0IZPNeEZ.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'kaizer-clear-battery',
      'tags' => 
      [
        0 => 'Battery',
      ],
      'sales_count' => 0,
      'views_count' => 21,
      'product_type' => 'battery',
      'flavor' => NULL,
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'KAIZER CLEAR (Battery)',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'stock' => 11,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Black',
      ],
      1 => 
      [
        'name' => 'Gold',
        'stock' => 4,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Gold',
      ],
      2 => 
      [
        'name' => 'Rose Gold',
        'stock' => 3,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Rose Gold',
      ],
      3 => 
      [
        'name' => 'Titanium Grey',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Titanium Grey',
      ],
    ],
  ],
  14 => 
  [
    'attributes' => 
    [
      'name' => 'FUNKY MONKEY RUSH 10K PODS',
    ],
    'data' => 
    [
      'name' => 'FUNKY MONKEY RUSH 10K PODS',
      'category' => 'Devices',
      'brand' => 'FUNKY MONKEY',
      'price' => 550,
      'description' => 'FUNKY MONKEY RUSH 10K PODS are prefilled vape pods designed to deliver rich flavor, smooth vapor, and up to 10,000 puffs of satisfying performance. Featuring mesh coil technology and a leak-resistant design, these pods provide consistent flavor and are compatible with the Funky Monkey Rush device for convenient everyday vaping.',
      'stock' => 27,
      'sku' => 'FKY',
      'original_price' => 650,
      'image' => 'products/m2nXMW5XYoDgzDP80ETDOSb1bsv2GYaNNkIEizWd.png',
      'badge' => 'sale',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'funky-monkey-rush-10k-pods',
      'tags' => 
      [
        0 => 'Disposable pods',
      ],
      'sales_count' => 0,
      'views_count' => 10,
      'product_type' => 'bundle',
      'flavor' => 'SEA ZEST - SEASALT LEMON, BURST MELLOW - LYCHEE MELON, GARDEN\'S HEART - STRAWBERRY, FRESH MINT - FRESH MINT, RED FROST - STRAWBERRY ICE CREAM, BUBBLE BOMB - BUBBLE GUM, CONTIS - CHEESE CAKE, BLACK GEMS - BLACK CURRANT, FRESH RED - WATERMELON, TANGY P',
      'bundle_pods' => 'bundle',
      'bundle_battery' => 'Battery/Device Included',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'FUNKY MONKEY RUSH 10K PODS',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'AZURE',
        'stock' => 7,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'AZURE',
      ],
      1 => 
      [
        'name' => 'BLACK',
        'stock' => 0,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'BLACK',
      ],
      2 => 
      [
        'name' => 'BLACK GEMS - BLACK CURRANT',
        'stock' => 18,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BLACK GEMS - BLACK CURRANT',
      ],
      3 => 
      [
        'name' => 'BUBBLE BOMB - BUBBLE GUM',
        'stock' => 9,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BUBBLE BOMB - BUBBLE GUM',
      ],
      4 => 
      [
        'name' => 'BURST MELLOW - LYCHEE MELON',
        'stock' => 6,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BURST MELLOW - LYCHEE MELON',
      ],
      5 => 
      [
        'name' => 'CONTIS - CHEESE CAKE',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'CONTIS - CHEESE CAKE',
      ],
      6 => 
      [
        'name' => 'FRESH MINT - FRESH MINT',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'FRESH MINT - FRESH MINT',
      ],
      7 => 
      [
        'name' => 'FRESH RED - WATERMELON',
        'stock' => 4,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'FRESH RED - WATERMELON',
      ],
      8 => 
      [
        'name' => 'GARDEN\'S HEART - STRAWBERRY',
        'stock' => 13,
        'reorder_level' => 2,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'GARDEN\'S HEART - STRAWBERRY',
      ],
      9 => 
      [
        'name' => 'GOLD',
        'stock' => 6,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'GOLD',
      ],
      10 => 
      [
        'name' => 'OLIVE',
        'stock' => 6,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'OLIVE',
      ],
      11 => 
      [
        'name' => 'RED FROST - STRAWBERRY ICE CREAM',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'RED FROST - STRAWBERRY ICE CREAM',
      ],
      12 => 
      [
        'name' => 'ROSE GOLD',
        'stock' => 3,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'ROSE GOLD',
      ],
      13 => 
      [
        'name' => 'SEA ZEST - SEASALT LEMON',
        'stock' => 9,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'SEA ZEST - SEASALT LEMON',
      ],
      14 => 
      [
        'name' => 'SPACE GRAY',
        'stock' => 5,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'SPACE GRAY',
      ],
      15 => 
      [
        'name' => 'TANGY PURPLE - GRAPE',
        'stock' => 10,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'TANGY PURPLE - GRAPE',
      ],
    ],
  ],
  15 => 
  [
    'attributes' => 
    [
      'name' => 'TOMORO T1 PODS',
    ],
    'data' => 
    [
      'name' => 'TOMORO T1 PODS',
      'category' => 'Devices',
      'brand' => 'TOMORO',
      'price' => 300,
      'description' => 'TOMORO T1 Pods
Compact, easy-to-use pods designed for the TOMORO T1, delivering smooth performance, consistent output, and convenient replacement for everyday use.',
      'stock' => 80,
      'sku' => 'TMRT',
      'original_price' => 250,
      'image' => 'products/h8Rue0O5FOTUCAPkKiIIhbtyv5wRidsIKpe5bYeN.jpg',
      'badge' => 'sale',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'tomoro-t1-pods',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 2,
      'product_type' => 'pods',
      'flavor' => 'APPOLO 13 - WATERMELON ICE, BIG BANG - BUBBLEGUM, DOUBLE YELLOW - DOUBLE MANGO, FATTY BOOM - YUMMY BEAR, FREEZING FUSION - FRUITY PUNCH, FROZEN PURPLE - TARO ICE CREAM, LAZZY RABBIT - LYCHEE RASPBERRY, RUBY KW - STRAWBERRY KIWI, TWINKLE STAR - STRAWB',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'TOMORO T1 PODS',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'APPOLO 13 - WATERMELON ICE',
        'stock' => 4,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'APPOLO 13 - WATERMELON ICE',
      ],
      1 => 
      [
        'name' => 'BIG BANG - BUBBLEGUM',
        'stock' => 1,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BIG BANG - BUBBLEGUM',
      ],
      2 => 
      [
        'name' => 'DOUBLE YELLOW - DOUBLE MANGO',
        'stock' => 14,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'DOUBLE YELLOW - DOUBLE MANGO',
      ],
      3 => 
      [
        'name' => 'FATTY BOOM - YUMMY BEAR',
        'stock' => 5,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'FATTY BOOM - YUMMY BEAR',
      ],
      4 => 
      [
        'name' => 'FREEZING FUSION - FRUITY PUNCH',
        'stock' => 5,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'FREEZING FUSION - FRUITY PUNCH',
      ],
      5 => 
      [
        'name' => 'FROZEN PURPLE - TARO ICE CREAM',
        'stock' => 6,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'FROZEN PURPLE - TARO ICE CREAM',
      ],
      6 => 
      [
        'name' => 'LAZZY RABBIT - LYCHEE RASPBERRY',
        'stock' => 11,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'LAZZY RABBIT - LYCHEE RASPBERRY',
      ],
      7 => 
      [
        'name' => 'RUBY KW - STRAWBERRY KIWI',
        'stock' => 19,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'RUBY KW - STRAWBERRY KIWI',
      ],
      8 => 
      [
        'name' => 'TWINKLE STAR - STRAWBERRY ICE CREAM',
        'stock' => 15,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'TWINKLE STAR - STRAWBERRY ICE CREAM',
      ],
    ],
  ],
  16 => 
  [
    'attributes' => 
    [
      'name' => 'TOMORO T1  BATTERY',
    ],
    'data' => 
    [
      'name' => 'TOMORO T1  BATTERY',
      'category' => 'Devices',
      'brand' => 'TOMORO',
      'price' => 229.99,
      'description' => 'TOMORO T1 Battery
A reliable, long-lasting battery designed to deliver steady power and efficient performance for the TOMORO T1. Built for daily use, it supports smooth operation, dependable range, and easy recharging for a convenient riding experience.',
      'stock' => 23,
      'sku' => 'TMBT',
      'original_price' => NULL,
      'image' => 'products/2kUOxkVCCas73jnaafbaIKi0F3bh66zrSfLon9W4.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'tomoro-t1-battery',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'battery',
      'flavor' => NULL,
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'TOMORO T1  BATTERY',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'stock' => 23,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Black',
      ],
    ],
  ],
  17 => 
  [
    'attributes' => 
    [
      'name' => 'XVAPE X-AROMA DELIGHT JUICE',
    ],
    'data' => 
    [
      'name' => 'XVAPE X-AROMA DELIGHT JUICE',
      'category' => 'E-Liquids',
      'brand' => 'XVAPE',
      'price' => 590,
      'description' => 'XVAPE X-AROMA Delight Juice 30ml delivers a smooth, flavorful vape experience in a compact bottle, perfect for everyday use. Crafted for consistent taste and satisfying vapor, it’s a convenient choice for users looking for a reliable e-liquid option.',
      'stock' => 37,
      'sku' => 'XVP',
      'original_price' => NULL,
      'image' => 'products/WsE4mEEWcH8HnEW3djegf0RIcRNY6u3f7SMaWCC9.png',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'xvape-x-aroma-delight-juice',
      'tags' => 
      [
        0 => 'Menthol',
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'e_liquid',
      'flavor' => 'COLD BREEZE - MINT, BUBBLE DREAM - BUBBLE GUM',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '10',
      ],
      'volume_ml' => 30,
      'product_name' => 'XVAPE X-AROMA DELIGHT JUICE',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'BUBBLE DREAM - BUBBLE GUM',
        'stock' => 18,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BUBBLE DREAM - BUBBLE GUM',
      ],
      1 => 
      [
        'name' => 'COLD BREEZE - MINT',
        'stock' => 19,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'COLD BREEZE - MINT',
      ],
    ],
  ],
  18 => 
  [
    'attributes' => 
    [
      'name' => 'XVAPE X-AROMA FREEZE  JUICE  ',
    ],
    'data' => 
    [
      'name' => 'XVAPE X-AROMA FREEZE  JUICE  ',
      'category' => 'E-Liquids',
      'brand' => 'XVAPE',
      'price' => 600,
      'description' => 'XVAPE X-AROMA Delight Juice delivers a smooth, flavorful vape experience in a compact bottle, perfect for everyday use. Crafted for consistent taste and satisfying vapor, it’s a convenient choice for users looking for a reliable e-liquid option.',
      'stock' => 13,
      'sku' => 'XVPA',
      'original_price' => NULL,
      'image' => 'products/hygWEFhovJLctsas2GEuZLgG3oInuYKFNkvcEoLz.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'xvape-x-aroma-freeze-juice',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'e_liquid',
      'flavor' => 'POLAR GLACIER - LYCHEE',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '18',
      ],
      'volume_ml' => 30,
      'product_name' => 'XVAPE X-AROMA FREEZE  JUICE  ',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'POLAR GLACIER - LYCHEE',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'POLAR GLACIER - LYCHEE',
      ],
    ],
  ],
  19 => 
  [
    'attributes' => 
    [
      'name' => 'THE POD FORMULA FREEBASE - MR ONURBS ',
    ],
    'data' => 
    [
      'name' => 'THE POD FORMULA FREEBASE - MR ONURBS ',
      'category' => 'E-Liquids',
      'brand' => 'POD FORMULA',
      'price' => 280,
      'description' => 'Non-mentholated flavors offer a smooth, full-bodied vape experience without the icy cooling effect. Ideal for adult vapers who prefer rich, clean flavor profiles with a warm and satisfying finish.',
      'stock' => 27,
      'sku' => 'TPFN',
      'original_price' => NULL,
      'image' => 'products/E9LdVf8mHdIvGO5BVYDmFwDen9u60LJErz6MCfCC.webp',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'the-pod-formula-freebase-mr-onurbs-2',
      'tags' => 
      [
        0 => 'NON MENTHOLATED FLAVORS',
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'e_liquid',
      'flavor' => 'BELGIAN DELIGHT - CHOCO MOUSSE',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '12',
      ],
      'volume_ml' => 58,
      'product_name' => 'THE POD FORMULA FREEBASE - MR ONURBS ',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'BELGIAN DELIGHT - CHOCO MOUSSE',
        'stock' => 27,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BELGIAN DELIGHT - CHOCO MOUSSE',
      ],
    ],
  ],
  20 => 
  [
    'attributes' => 
    [
      'name' => 'BLISS LITE ',
    ],
    'data' => 
    [
      'name' => 'BLISS LITE ',
      'category' => 'E-Liquids',
      'brand' => 'BLISS LITE',
      'price' => 150,
      'description' => 'BLISS Lite 30ml offers a smooth and flavorful vape experience in a compact bottle. Designed for everyday use, it delivers satisfying taste and consistent vapor without an overpowering finish.',
      'stock' => 48,
      'sku' => 'BLS',
      'original_price' => NULL,
      'image' => 'products/HXD0qXncoEDf7QWiNkC02PlvJxIqFy1FdwxWNqus.png',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'bliss-lite',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'e_liquid',
      'flavor' => 'ERY4 - EXTREME RY4, NRY4 - NUTTY RY4, SRY4 - STRAWBERRY RY4, BCC - BLUEBERRY CHEESECAKE',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '3',
      ],
      'volume_ml' => 30,
      'product_name' => 'BLISS LITE ',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'BCC - BLUEBERRY CHEESECAKE',
        'stock' => 8,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'BCC - BLUEBERRY CHEESECAKE',
      ],
      1 => 
      [
        'name' => 'ERY4 - EXTREME RY4',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'ERY4 - EXTREME RY4',
      ],
      2 => 
      [
        'name' => 'NRY4 - NUTTY RY4',
        'stock' => 9,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'NRY4 - NUTTY RY4',
      ],
      3 => 
      [
        'name' => 'SRY4 - STRAWBERRY RY4',
        'stock' => 19,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'SRY4 - STRAWBERRY RY4',
      ],
    ],
  ],
  21 => 
  [
    'attributes' => 
    [
      'name' => 'Black Elite V2',
    ],
    'data' => 
    [
      'name' => 'Black Elite V2',
      'category' => 'Pod System',
      'brand' => 'BLack',
      'price' => 550,
      'description' => 'Black Elite is a popular disposable vape and pod system brand that became widely known in the Philippines for its strong icy flavors, long-lasting puff count, and affordable devices. The brand first gained popularity through the original Black Elite V1, then later released the upgraded Black Elite V2 with improved mesh coils, rechargeable Type-C charging, LED indicators, and up to 12,000 puffs.',
      'stock' => 123,
      'sku' => 'BLK-V2',
      'original_price' => NULL,
      'image' => 'products/Ty3fN8kd2WcsKHZ7pvMzcTHOAXT323r18QC8OK3t.webp',
      'badge' => 'new',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => 0,
      'slug' => 'black-elite-v2',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 0,
      'product_type' => 'bundle',
      'flavor' => 'Red Pulp (Watermelon), Very More (Mixed Berries), Trouble Purple (Grapes), Very Baguio (Strawberry), Black Wave (Blackcurrant), Yellow Summer (Mango)',
      'bundle_pods' => 'Any Flavors',
      'bundle_battery' => 'Battery/Pods Included',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'product_name' => 'Black Elite V2',
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'stock' => 40,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Black',
      ],
      1 => 
      [
        'name' => 'Black Wave (Blackcurrant)',
        'stock' => 30,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Black Wave (Blackcurrant)',
      ],
      2 => 
      [
        'name' => 'Gold',
        'stock' => 33,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Gold',
      ],
      3 => 
      [
        'name' => 'Orange',
        'stock' => 50,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'color',
        'flavor' => 'Orange',
      ],
      4 => 
      [
        'name' => 'Red Pulp (Watermelon)',
        'stock' => 29,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Red Pulp (Watermelon)',
      ],
      5 => 
      [
        'name' => 'Trouble Purple (Grapes)',
        'stock' => 30,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Trouble Purple (Grapes)',
      ],
      6 => 
      [
        'name' => 'Very Baguio (Strawberry)',
        'stock' => 30,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Very Baguio (Strawberry)',
      ],
      7 => 
      [
        'name' => 'Very More (Mixed Berries)',
        'stock' => 31,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Very More (Mixed Berries)',
      ],
      8 => 
      [
        'name' => 'Yellow Summer (Mango)',
        'stock' => 30,
        'reorder_level' => 5,
        'is_active' => 1,
        'option_type' => 'flavor',
        'flavor' => 'Yellow Summer (Mango)',
      ],
    ],
  ],
];
    }
}