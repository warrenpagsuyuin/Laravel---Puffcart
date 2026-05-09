<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductFlavor;

class AddChillaxFlavorsSeeder extends Seeder
{
    public function run(): void
    {
        $productName = 'CHILLAX INFINITE KIT ( POD & BATTERY )';

        $product = Product::whereRaw('LOWER(name) = ?', [mb_strtolower($productName)])->first();

        if (!$product) {
            // fallback: search by substring 'chillax'
            $matches = Product::where('name', 'like', '%CHILLAX%')->get();
            if ($matches->isEmpty()) {
                $this->command->error("Product '{$productName}' not found, and no product with 'CHILLAX' in name.");
                return;
            }

            if ($matches->count() > 1) {
                $this->command->info("Multiple products matched 'CHILLAX'; using the first one: {$matches->first()->name}");
            }

            $product = $matches->first();
        }

        $flavors = [
            'COSMIC CRUSH - GRAPES',
            'SLICK KICK - SOUR APPLE',
            'VERY MELLOW - WATERMELON',
            'VIOLET FUSION - TARO ICE CREAM',
            'PINK HARMONY - JUICE STRAWBERRY',
            'TWILIGHT WILLOW - BLACKCURRANT',
            'SILVER DYNASTY - MENTHOL ICE',
            'CRYSTAL WINK - BUBBLEGUM',
            'THUNDER BLAZED - GATORADE',
            'RUSTIC HAZE - CLASSIC TOBACCO',
            'IVORY SYMPHONY - LYCHEE',
            'HAPPY BUDDIES - YAKULT',
        ];

        $created = 0;
        foreach ($flavors as $name) {
            $exists = ProductFlavor::where('product_id', $product->id)
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->exists();

            if ($exists) {
                continue;
            }

            ProductFlavor::create([
                'product_id' => $product->id,
                'name' => $name,
                'stock' => 0,
                'reorder_level' => 5,
                'is_active' => true,
            ]);

            $created++;
        }

        $this->command->info("Added {$created} new flavors to product '{$product->name}'.");
    }
}
