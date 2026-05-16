<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products') || !Schema::hasColumn('products', 'product_type')) {
            return;
        }

        $query = DB::table('products');
        $hasFilter = false;

        if (Schema::hasColumn('products', 'category')) {
            $hasFilter = true;
            $query->where(function ($query) {
                $query->where('category', 'like', '%E-Liquid%')
                    ->orWhere('category', 'like', '%E Liquid%');
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('products', 'category_id')) {
            $categoryIds = DB::table('categories')
                ->where('slug', 'e-liquids')
                ->orWhere('name', 'like', '%E-Liquid%')
                ->orWhere('name', 'like', '%E Liquid%')
                ->pluck('id');

            if ($categoryIds->isNotEmpty()) {
                $hasFilter = true;
                if (Schema::hasColumn('products', 'category')) {
                    $query->orWhereIn('category_id', $categoryIds);
                } else {
                    $query->whereIn('category_id', $categoryIds);
                }
            }
        }

        if (!$hasFilter) {
            return;
        }

        $query->orderBy('id')->get()->each(function ($product): void {
            $payload = ['product_type' => 'e_liquid'];

            if (Schema::hasColumn('products', 'nicotine_type') && empty($product->nicotine_type)) {
                $searchText = Str::lower(collect([
                    $product->name ?? '',
                    $product->sku ?? '',
                    $product->description ?? '',
                    $product->tags ?? '',
                ])->implode(' '));

                $payload['nicotine_type'] = Str::contains($searchText, ['salt', 'saltnic', 'salt nic'])
                    ? 'saltnic'
                    : 'freebase';
            }

            DB::table('products')->where('id', $product->id)->update($payload);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('products') || !Schema::hasColumn('products', 'product_type')) {
            return;
        }

        DB::table('products')
            ->where('product_type', 'e_liquid')
            ->update(['product_type' => 'other']);
    }
};
