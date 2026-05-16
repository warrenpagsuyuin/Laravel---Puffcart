<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductFlavor;

class AddKaizerFlavorsSeeder extends Seeder
{
    public function run(): void
    {
        $pods = [
            'SHIROTA ICE - YAKULT',
            'TOKYO JADE ICE - MATCHA',
        ];

        $batteries = [
            'GOLD',
            'ROSE GOLD',
            'RED',
            'BLUE',
            'TITANIUM GREY',
        ];

        $products = Product::where('name', 'like', '%KAIZER%')
            ->orWhere('name', 'like', '%KAISER%')
            ->get();

        if ($products->isEmpty()) {
            $this->command->error('No Kaizer products found (searched for "KAIZER"/"KAISER").');
            return;
        }

        $added = 0;

        foreach ($products as $product) {
            $nameUp = mb_strtoupper($product->name);
            $toAdd = [];

            if ($product->product_type === Product::TYPE_PODS || str_contains($nameUp, 'POD')) {
                foreach ($pods as $pod) {
                    $toAdd[] = ['name' => $pod, 'option_type' => ProductFlavor::TYPE_FLAVOR];
                }
            }

            if ($product->product_type === Product::TYPE_BATTERY || str_contains($nameUp, 'BATTERY')) {
                foreach ($batteries as $battery) {
                    $toAdd[] = ['name' => $battery, 'option_type' => ProductFlavor::TYPE_COLOR];
                }
            }

            // If product is a bundle or no detection, try to infer by name sections
            if (empty($toAdd)) {
                if ($product->product_type === Product::TYPE_BUNDLE || str_contains($nameUp, 'POD & BATTERY') || str_contains($nameUp, 'POD & BATTERY')) {
                    foreach ($pods as $pod) {
                        $toAdd[] = ['name' => $pod, 'option_type' => ProductFlavor::TYPE_FLAVOR];
                    }
                    foreach ($batteries as $battery) {
                        $toAdd[] = ['name' => $battery, 'option_type' => ProductFlavor::TYPE_COLOR];
                    }
                }
            }

            if (empty($toAdd)) {
                $this->command->info("Skipping product '{$product->name}' — couldn't determine which flavors to add.");
                continue;
            }

            foreach (collect($toAdd)->unique(fn ($option) => $option['option_type'] . ':' . mb_strtolower($option['name'])) as $option) {
                $flavorName = $option['name'];
                $exists = ProductFlavor::where('product_id', $product->id)
                    ->where('option_type', $option['option_type'])
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($flavorName)])
                    ->exists();

                if ($exists) {
                    continue;
                }

                ProductFlavor::create([
                    'product_id' => $product->id,
                    'name' => $flavorName,
                    'option_type' => $option['option_type'],
                    'stock' => 0,
                    'reorder_level' => 5,
                    'is_active' => true,
                ]);

                $added++;
            }

            $this->command->info("Processed product: {$product->name}");
        }

        $this->command->info("Added {$added} new Kaizer flavor/color entries (prices unchanged).");
    }
}
