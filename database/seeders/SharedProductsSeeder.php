<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SharedProductsSeeder extends Seeder
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
                    $product->flavors()->updateOrCreate(
                        ['name' => $flavor['name']],
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
      'name' => 'CHILLAX INFINITE KIT ( POD & BATTERY)',
    ],
    'data' => 
    [
      'name' => 'CHILLAX INFINITE KIT ( POD & BATTERY)',
      'category' => 'Devices',
      'brand' => 'CHILLAX',
      'product_type' => 'bundle',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => 'COSMIC CRUSH - GRAPES, CRYSTAL WINK - BUBBLEGUM, HAPPY BUDDIES - YAKULT, IVORY SYMPHONY - LYCHEE, PINK HARMONY - JUICE STRAWBERRY, RUSTIC HAZE - CLASSIC TOBACCO, SILVER DYNASTY - MENTHOL ICE, SLICK KICK - SOUR APPLE, THUNDER BLAZED - GATORADE, TWILIG',
      'bundle_pods' => 'Any Flavors',
      'bundle_battery' => 'Battery/Device included',
      'price' => '650.00',
      'description' => 'CHILLAX INFINITE KIT (POD & BATTERY) is a sleek and powerful pod vape device designed for smooth flavor delivery and long-lasting performance. Featuring an ultra-thin body, dual mesh coil technology, and a clear e-liquid viewing pod, it delivers rich flavor and satisfying vapor from the first puff to the last. The device includes a rechargeable 600mAh battery, Type-C charging, and up to 25,000 puffs for extended use. Its compact and stylish design makes it perfect for everyday vaping convenience and portability.',
      'stock' => 24,
      'sku' => 'CHLX',
      'original_price' => NULL,
      'image' => 'products/BjK6QHmekdl9vV4DehROyydLeshsKVJFshL4lx20.jpg',
      'badge' => 'new',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'chillax-infinite-kit-pod-battery',
      'tags' => 
      [
        0 => 'Disposable pods',
      ],
      'sales_count' => 0,
      'views_count' => 24,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'option_type' => 'color',
        'stock' => 24,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'COSMIC CRUSH - GRAPES',
        'option_type' => 'flavor',
        'stock' => 19,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      2 => 
      [
        'name' => 'CRYSTAL WINK - BUBBLEGUM',
        'option_type' => 'flavor',
        'stock' => 16,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      3 => 
      [
        'name' => 'HAPPY BUDDIES - YAKULT',
        'option_type' => 'flavor',
        'stock' => 7,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      4 => 
      [
        'name' => 'IVORY SYMPHONY - LYCHEE',
        'option_type' => 'flavor',
        'stock' => 17,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      5 => 
      [
        'name' => 'PINK HARMONY - JUICE STRAWBERRY',
        'option_type' => 'flavor',
        'stock' => 24,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      6 => 
      [
        'name' => 'RUSTIC HAZE - CLASSIC TOBACCO',
        'option_type' => 'flavor',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      7 => 
      [
        'name' => 'SILVER DYNASTY - MENTHOL ICE',
        'option_type' => 'flavor',
        'stock' => 1,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      8 => 
      [
        'name' => 'SLICK KICK - SOUR APPLE',
        'option_type' => 'flavor',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      9 => 
      [
        'name' => 'THUNDER BLAZED - GATORADE',
        'option_type' => 'flavor',
        'stock' => 1,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      10 => 
      [
        'name' => 'TWILIGHT WILLOW - BLACKCURRANT',
        'option_type' => 'flavor',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      11 => 
      [
        'name' => 'VERY MELLOW - WATERMELON',
        'option_type' => 'flavor',
        'stock' => 7,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      12 => 
      [
        'name' => 'VIOLET FUSION - TARO ICE CREAM',
        'option_type' => 'flavor',
        'stock' => 14,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  1 => 
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
      'product_type' => 'pods',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => 'SLICK KICK - SOUR APPLE, COSMIC CRUSH - GRAPES, VERY MELLOW - WATERMELON, PINK HARMONY - JUICE STRAWBERRY, TWILIGHT WILLOW - BLACKCURRANT, SILVER DYNASTY - MENTHOL ICE, SILKY CLOUDS - VANILLA ICE CREAM, CRYSTAL WINK - BUBBLEGUM, MIDORI ZEN - MATCHA',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '450.00',
      'description' => 'CHILLAX INFINITE KIT (PODS ONLY) delivers a smooth, flavorful, and hassle-free vaping experience with its advanced dual mesh coil technology and leak-resistant pod design. Made for the Chillax Infinite device, these prefilled pods provide rich flavor, satisfying vapor production, and consistent performance from the first puff to the last. Its transparent pod design allows easy e-liquid visibility, while the compact and lightweight build makes it perfect for daily use and portability.',
      'stock' => 98,
      'sku' => 'CHLXPDS',
      'original_price' => '550.00',
      'image' => 'products/hkAL1TqGm3t9M4t2nxv15mzDIgRZFnW3WJ5hnzaL.jpg',
      'badge' => 'sale',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'chillax-infinite-pod-only',
      'tags' => 
      [
        0 => 'PODS',
      ],
      'sales_count' => 0,
      'views_count' => 7,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'COSMIC CRUSH - GRAPES',
        'option_type' => 'flavor',
        'stock' => 5,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'CRYSTAL WINK - BUBBLEGUM',
        'option_type' => 'flavor',
        'stock' => 8,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      2 => 
      [
        'name' => 'MIDORI ZEN - MATCHA',
        'option_type' => 'flavor',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      3 => 
      [
        'name' => 'PINK HARMONY - JUICE STRAWBERRY',
        'option_type' => 'flavor',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      4 => 
      [
        'name' => 'SILKY CLOUDS - VANILLA ICE CREAM',
        'option_type' => 'flavor',
        'stock' => 13,
        'reorder_level' => 4,
        'is_active' => 1,
      ],
      5 => 
      [
        'name' => 'SILVER DYNASTY - MENTHOL ICE',
        'option_type' => 'flavor',
        'stock' => 16,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      6 => 
      [
        'name' => 'SLICK KICK - SOUR APPLE',
        'option_type' => 'flavor',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      7 => 
      [
        'name' => 'TWILIGHT WILLOW - BLACKCURRANT',
        'option_type' => 'flavor',
        'stock' => 21,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      8 => 
      [
        'name' => 'VERY MELLOW - WATERMELON',
        'option_type' => 'flavor',
        'stock' => 8,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  2 => 
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
      'product_type' => 'pods',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => 'PASSION RED - STRAWBERRY, JITTER MUD - CARAMEL MACCHIATO, BLUE FREEZE - BLUEBERRY, BUBBLE BLOWER - BUBBLEGUM, CHEER BLAST - LYCHEE',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '300.00',
      'description' => 'RYUU BOOST 12000 is a rechargeable pod vape that delivers smooth flavor, strong vapor, and up to 12,000 puffs. It features mesh coil technology, Type-C charging, and a sleek portable design for everyday vaping convenience.',
      'stock' => 53,
      'sku' => 'RYU',
      'original_price' => '0.00',
      'image' => 'products/4ZW2GaksC19yWPY4A4ay175WvDf0JC66kceoiVJ3.jpg',
      'badge' => 'new',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'ryuu-boost-12000',
      'tags' => 
      [
        0 => 'PODS',
      ],
      'sales_count' => 0,
      'views_count' => 0,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'BLUE FREEZE - BLUEBERRY',
        'option_type' => 'flavor',
        'stock' => 7,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'BUBBLE BLOWER - BUBBLEGUM',
        'option_type' => 'flavor',
        'stock' => 8,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      2 => 
      [
        'name' => 'CHEER BLAST - LYCHEE',
        'option_type' => 'flavor',
        'stock' => 10,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      3 => 
      [
        'name' => 'JITTER MUD - CARAMEL MACCHIATO',
        'option_type' => 'flavor',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      4 => 
      [
        'name' => 'PASSION RED - STRAWBERRY',
        'option_type' => 'flavor',
        'stock' => 16,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  3 => 
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
      'product_type' => 'battery',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => NULL,
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '350.00',
      'description' => 'RYUU BOOST 12000 BATTERY is a rechargeable vape device with a sleek and portable design, built for reliable performance and smooth power delivery. It features a long-lasting battery, Type-C charging, and compatibility with RYUU BOOST pods for convenient everyday use.',
      'stock' => 14,
      'sku' => 'RYUBT',
      'original_price' => NULL,
      'image' => 'products/Tf4Loxw5hY0Y1Ip0IlCIf9YiSLo6NZ57RwztoxKy.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'ryuu-boost-12000-battery',
      'tags' => 
      [
        0 => 'Battery',
      ],
      'sales_count' => 0,
      'views_count' => 2,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'option_type' => 'color',
        'stock' => 14,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  4 => 
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
      'product_type' => 'pods',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => 'SHIROTA ICE - YAKULT  ',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '430.00',
      'description' => 'KAIZER CLEAR is a sleek and rechargeable pod vape designed for smooth flavor delivery and reliable everyday performance. It features a transparent pod design, mesh coil technology, adjustable airflow, and a long-lasting battery for a clean and satisfying vaping experience.',
      'stock' => 24,
      'sku' => 'KZR',
      'original_price' => NULL,
      'image' => 'products/ls0mT4REERm7w3nCFF1f3KL3lYOY3xZWtR4Bviww.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'kaizer-clear',
      'tags' => 
      [
        0 => 'Pods',
      ],
      'sales_count' => 0,
      'views_count' => 9,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'SHIROTA ICE - YAKULT',
        'option_type' => 'flavor',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'TOKYO JADE ICE - MATCHA',
        'option_type' => 'flavor',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  5 => 
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
      'product_type' => 'battery',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => NULL,
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '350.00',
      'description' => 'KAIZER CLEAR BATTERY is a rechargeable vape device with a compact and stylish transparent design, built for smooth and reliable performance. It features a long-lasting battery, Type-C charging, and compatibility with Kaizer pods for convenient daily vaping.',
      'stock' => 20,
      'sku' => 'KZRBT',
      'original_price' => NULL,
      'image' => 'products/UrTASz6qm7eqhVGQa29uNrBXoRp4DZ9z0IZPNeEZ.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'kaizer-clear-battery',
      'tags' => 
      [
        0 => 'Battery',
      ],
      'sales_count' => 0,
      'views_count' => 21,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'option_type' => 'color',
        'stock' => 11,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'Gold',
        'option_type' => 'color',
        'stock' => 4,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      2 => 
      [
        'name' => 'Rose Gold',
        'option_type' => 'color',
        'stock' => 3,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      3 => 
      [
        'name' => 'Titanium Grey',
        'option_type' => 'color',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  6 => 
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
      'product_type' => 'bundle',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => 'SEA ZEST - SEASALT LEMON, BURST MELLOW - LYCHEE MELON, GARDEN\'S HEART - STRAWBERRY, FRESH MINT - FRESH MINT, RED FROST - STRAWBERRY ICE CREAM, BUBBLE BOMB - BUBBLE GUM, CONTIS - CHEESE CAKE, BLACK GEMS - BLACK CURRANT, FRESH RED - WATERMELON, TANGY P',
      'bundle_pods' => 'bundle',
      'bundle_battery' => 'Battery/Device Included',
      'price' => '550.00',
      'description' => 'FUNKY MONKEY RUSH 10K PODS are prefilled vape pods designed to deliver rich flavor, smooth vapor, and up to 10,000 puffs of satisfying performance. Featuring mesh coil technology and a leak-resistant design, these pods provide consistent flavor and are compatible with the Funky Monkey Rush device for convenient everyday vaping.',
      'stock' => 27,
      'sku' => 'FKY',
      'original_price' => '650.00',
      'image' => 'products/m2nXMW5XYoDgzDP80ETDOSb1bsv2GYaNNkIEizWd.png',
      'badge' => 'sale',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'funky-monkey-rush-10k-pods',
      'tags' => 
      [
        0 => 'Disposable pods',
      ],
      'sales_count' => 0,
      'views_count' => 10,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'AZURE',
        'option_type' => 'color',
        'stock' => 7,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'BLACK',
        'option_type' => 'color',
        'stock' => 0,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      2 => 
      [
        'name' => 'BLACK GEMS - BLACK CURRANT',
        'option_type' => 'flavor',
        'stock' => 18,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      3 => 
      [
        'name' => 'BUBBLE BOMB - BUBBLE GUM',
        'option_type' => 'flavor',
        'stock' => 9,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      4 => 
      [
        'name' => 'BURST MELLOW - LYCHEE MELON',
        'option_type' => 'flavor',
        'stock' => 6,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      5 => 
      [
        'name' => 'CONTIS - CHEESE CAKE',
        'option_type' => 'flavor',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      6 => 
      [
        'name' => 'FRESH MINT - FRESH MINT',
        'option_type' => 'flavor',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      7 => 
      [
        'name' => 'FRESH RED - WATERMELON',
        'option_type' => 'flavor',
        'stock' => 4,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      8 => 
      [
        'name' => 'GARDEN\'S HEART - STRAWBERRY',
        'option_type' => 'flavor',
        'stock' => 13,
        'reorder_level' => 2,
        'is_active' => 1,
      ],
      9 => 
      [
        'name' => 'GOLD',
        'option_type' => 'color',
        'stock' => 6,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      10 => 
      [
        'name' => 'OLIVE',
        'option_type' => 'color',
        'stock' => 6,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      11 => 
      [
        'name' => 'RED FROST - STRAWBERRY ICE CREAM',
        'option_type' => 'flavor',
        'stock' => 0,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      12 => 
      [
        'name' => 'ROSE GOLD',
        'option_type' => 'color',
        'stock' => 3,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      13 => 
      [
        'name' => 'SEA ZEST - SEASALT LEMON',
        'option_type' => 'flavor',
        'stock' => 2,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      14 => 
      [
        'name' => 'SPACE GRAY',
        'option_type' => 'color',
        'stock' => 5,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      15 => 
      [
        'name' => 'TANGY PURPLE - GRAPE',
        'option_type' => 'flavor',
        'stock' => 10,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  7 => 
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
      'product_type' => 'pods',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => 'APPOLO 13 - WATERMELON ICE, BIG BANG - BUBBLEGUM, DOUBLE YELLOW - DOUBLE MANGO, FATTY BOOM - YUMMY BEAR, FREEZING FUSION - FRUITY PUNCH, FROZEN PURPLE - TARO ICE CREAM, LAZZY RABBIT - LYCHEE RASPBERRY, RUBY KW - STRAWBERRY KIWI, TWINKLE STAR - STRAWB',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '300.00',
      'description' => 'TOMORO T1 Pods
Compact, easy-to-use pods designed for the TOMORO T1, delivering smooth performance, consistent output, and convenient replacement for everyday use.',
      'stock' => 80,
      'sku' => 'TMRT',
      'original_price' => '250.00',
      'image' => 'products/h8Rue0O5FOTUCAPkKiIIhbtyv5wRidsIKpe5bYeN.jpg',
      'badge' => 'sale',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'tomoro-t1-pods',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 1,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'APPOLO 13 - WATERMELON ICE',
        'option_type' => 'flavor',
        'stock' => 4,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'BIG BANG - BUBBLEGUM',
        'option_type' => 'flavor',
        'stock' => 1,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      2 => 
      [
        'name' => 'DOUBLE YELLOW - DOUBLE MANGO',
        'option_type' => 'flavor',
        'stock' => 14,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      3 => 
      [
        'name' => 'FATTY BOOM - YUMMY BEAR',
        'option_type' => 'flavor',
        'stock' => 5,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      4 => 
      [
        'name' => 'FREEZING FUSION - FRUITY PUNCH',
        'option_type' => 'flavor',
        'stock' => 5,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      5 => 
      [
        'name' => 'FROZEN PURPLE - TARO ICE CREAM',
        'option_type' => 'flavor',
        'stock' => 6,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      6 => 
      [
        'name' => 'LAZZY RABBIT - LYCHEE RASPBERRY',
        'option_type' => 'flavor',
        'stock' => 11,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      7 => 
      [
        'name' => 'RUBY KW - STRAWBERRY KIWI',
        'option_type' => 'flavor',
        'stock' => 19,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      8 => 
      [
        'name' => 'TWINKLE STAR - STRAWBERRY ICE CREAM',
        'option_type' => 'flavor',
        'stock' => 15,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  8 => 
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
      'product_type' => 'battery',
      'nicotine_type' => NULL,
      'nicotine_strengths' => NULL,
      'volume_ml' => NULL,
      'flavor' => NULL,
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '229.99',
      'description' => 'TOMORO T1 Battery
A reliable, long-lasting battery designed to deliver steady power and efficient performance for the TOMORO T1. Built for daily use, it supports smooth operation, dependable range, and easy recharging for a convenient riding experience.',
      'stock' => 16,
      'sku' => 'TMBT',
      'original_price' => NULL,
      'image' => 'products/2kUOxkVCCas73jnaafbaIKi0F3bh66zrSfLon9W4.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'tomoro-t1-battery',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 0,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'Black',
        'option_type' => 'color',
        'stock' => 16,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  9 => 
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
      'product_type' => 'e_liquid',
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '10',
      ],
      'volume_ml' => 30,
      'flavor' => 'COLD BREEZE - MINT, BUBBLE DREAM - BUBBLE GUM',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '590.00',
      'description' => 'XVAPE X-AROMA Delight Juice 30ml delivers a smooth, flavorful vape experience in a compact bottle, perfect for everyday use. Crafted for consistent taste and satisfying vapor, it’s a convenient choice for users looking for a reliable e-liquid option.',
      'stock' => 39,
      'sku' => 'XVP',
      'original_price' => NULL,
      'image' => 'products/WsE4mEEWcH8HnEW3djegf0RIcRNY6u3f7SMaWCC9.png',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'xvape-x-aroma-delight-juice',
      'tags' => 
      [
        0 => 'Menthol',
      ],
      'sales_count' => 0,
      'views_count' => 0,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'BUBBLE DREAM - BUBBLE GUM',
        'option_type' => 'flavor',
        'stock' => 20,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'COLD BREEZE - MINT',
        'option_type' => 'flavor',
        'stock' => 19,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  10 => 
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
      'product_type' => 'e_liquid',
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '18',
      ],
      'volume_ml' => 30,
      'flavor' => 'POLAR GLACIER - LYCHEE',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '600.00',
      'description' => 'XVAPE X-AROMA Delight Juice delivers a smooth, flavorful vape experience in a compact bottle, perfect for everyday use. Crafted for consistent taste and satisfying vapor, it’s a convenient choice for users looking for a reliable e-liquid option.',
      'stock' => 13,
      'sku' => 'XVPA',
      'original_price' => NULL,
      'image' => 'products/hygWEFhovJLctsas2GEuZLgG3oInuYKFNkvcEoLz.jpg',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'xvape-x-aroma-freeze-juice',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 0,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'POLAR GLACIER - LYCHEE',
        'option_type' => 'flavor',
        'stock' => 13,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  11 => 
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
      'product_type' => 'e_liquid',
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '12',
      ],
      'volume_ml' => 60,
      'flavor' => 'RED PURP - STRAWBERRY GRAPES',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '240.00',
      'description' => 'THE POD FORMULA Freebase – Mr. Onurbs 60ml features mentholated flavors for a cool, refreshing vape experience with a smooth icy finish. Crafted for adult vapers who enjoy bold flavor, satisfying vapor, and a crisp menthol kick.',
      'stock' => 16,
      'sku' => 'TPFM',
      'original_price' => '300.00',
      'image' => 'products/z1P0jnSN5CVqpMLR3kIEKq0Z38t8FNSA50SDVxiQ.webp',
      'badge' => 'sale',
      'reorder_level' => 5,
      'is_featured' => 1,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'the-pod-formula-freebase-mr-onurbs',
      'tags' => 
      [
        0 => 'MENTHOLATED FLAVORS',
      ],
      'sales_count' => 0,
      'views_count' => 0,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'RED PURP - STRAWBERRY GRAPES',
        'option_type' => 'flavor',
        'stock' => 16,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  12 => 
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
      'product_type' => 'e_liquid',
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '12',
      ],
      'volume_ml' => 58,
      'flavor' => 'BELGIAN DELIGHT - CHOCO MOUSSE',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '280.00',
      'description' => 'Non-mentholated flavors offer a smooth, full-bodied vape experience without the icy cooling effect. Ideal for adult vapers who prefer rich, clean flavor profiles with a warm and satisfying finish.',
      'stock' => 9,
      'sku' => 'TPFN',
      'original_price' => NULL,
      'image' => 'products/E9LdVf8mHdIvGO5BVYDmFwDen9u60LJErz6MCfCC.webp',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'the-pod-formula-freebase-mr-onurbs-2',
      'tags' => 
      [
        0 => 'NON MENTHOLATED FLAVORS',
      ],
      'sales_count' => 0,
      'views_count' => 0,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'BELGIAN DELIGHT - CHOCO MOUSSE',
        'option_type' => 'flavor',
        'stock' => 9,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
  13 => 
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
      'product_type' => 'e_liquid',
      'nicotine_type' => 'freebase',
      'nicotine_strengths' => 
      [
        0 => '3',
      ],
      'volume_ml' => 30,
      'flavor' => 'ERY4 - EXTREME RY4, NRY4 - NUTTY RY4, SRY4 - STRAWBERRY RY4, BCC - BLUEBERRY CHEESECAKE',
      'bundle_pods' => NULL,
      'bundle_battery' => NULL,
      'price' => '150.00',
      'description' => 'BLISS Lite 30ml offers a smooth and flavorful vape experience in a compact bottle. Designed for everyday use, it delivers satisfying taste and consistent vapor without an overpowering finish.',
      'stock' => 48,
      'sku' => 'BLS',
      'original_price' => NULL,
      'image' => 'products/HXD0qXncoEDf7QWiNkC02PlvJxIqFy1FdwxWNqus.png',
      'badge' => 'none',
      'reorder_level' => 5,
      'is_featured' => 0,
      'is_active' => 1,
      'rating' => '0.0',
      'slug' => 'bliss-lite',
      'tags' => 
      [
      ],
      'sales_count' => 0,
      'views_count' => 0,
    ],
    'flavors' => 
    [
      0 => 
      [
        'name' => 'BCC - BLUEBERRY CHEESECAKE',
        'option_type' => 'flavor',
        'stock' => 8,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      1 => 
      [
        'name' => 'ERY4 - EXTREME RY4',
        'option_type' => 'flavor',
        'stock' => 12,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      2 => 
      [
        'name' => 'NRY4 - NUTTY RY4',
        'option_type' => 'flavor',
        'stock' => 9,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
      3 => 
      [
        'name' => 'SRY4 - STRAWBERRY RY4',
        'option_type' => 'flavor',
        'stock' => 19,
        'reorder_level' => 5,
        'is_active' => 1,
      ],
    ],
  ],
];
    }
}