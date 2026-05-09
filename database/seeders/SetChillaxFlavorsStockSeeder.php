<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductFlavor;

class SetChillaxFlavorsStockSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::where('name', 'like', '%CHILLAX%')->first();

        if (!$product) {
            $this->command->error('No Chillax product found to set stocks.');
            return;
        }

        $flavors = ProductFlavor::where('product_id', $product->id)->get();

        if ($flavors->isEmpty()) {
            $this->command->info('No flavors found for the Chillax product.');
            return;
        }

        $updated = 0;
        foreach ($flavors as $flavor) {
            $flavor->stock = rand(1, 25);
            $flavor->reorder_level = max(1, intval($flavor->reorder_level));
            $flavor->save();
            $updated++;
            $this->command->info("Set {$flavor->name} => {$flavor->stock}");
        }

        // Sync product stock from flavors
        try {
            $product->syncStockFromFlavors();
        } catch (\Throwable $e) {
            $this->command->error('Failed to sync product stock: ' . $e->getMessage());
        }

        $this->command->info("Updated stock for {$updated} flavors and synced product stock.");
    }
}
