<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class DeleteNoImageProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $query = Product::whereNull('image')->orWhere('image', '');
        $count = $query->count();

        if ($count === 0) {
            $this->command->info('No products without image found.');
            return;
        }

        $query->delete();

        $this->command->info("Deleted {$count} products without image.");
    }
}
