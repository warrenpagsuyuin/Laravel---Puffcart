<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductFlavor;

class SetKaizerClearStocksSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::where('name', 'like', '%KAIZER CLEAR%')->first();

        if (!$product) {
            $this->command->error('KAIZER CLEAR product not found.');
            return;
        }

        // Desired stocks: pods => 12 each, batteries => 20 each
        $mapping = [
            'SHIROTA ICE - YAKULT' => 12,
            'TOKYO JADE ICE - MATCHA' => 12,
            'GOLD' => 20,
            'ROSE GOLD' => 20,
            'RED' => 20,
            'BLUE' => 20,
            'TITANIUM GREY' => 20,
        ];

        $updated = 0;

        foreach ($mapping as $name => $stock) {
            $flavor = ProductFlavor::where('product_id', $product->id)
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                ->first();

            if (!$flavor) {
                $this->command->info("Flavor '{$name}' not found for product {$product->name}, skipping.");
                continue;
            }

            $flavor->stock = (int) $stock;
            $flavor->is_active = true;
            $flavor->save();
            $this->command->info("Set {$flavor->name} => {$flavor->stock}");
            $updated++;
        }

        // Sync product stock from flavors
        $product->syncStockFromFlavors();

        $this->command->info("Updated stock for {$updated} Kaizer Clear flavors and synced product stock.");
    }
}
