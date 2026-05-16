<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_flavors')) {
            Schema::create('product_flavors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->unsignedInteger('stock')->default(0);
                $table->unsignedInteger('reorder_level')->default(5);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['product_id', 'name']);
                $table->index(['product_id', 'is_active', 'stock']);
            });
        }

        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'product_flavor_id')) {
                $table->foreignId('product_flavor_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained('product_flavors')
                    ->nullOnDelete();
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_flavor_id')) {
                $table->foreignId('product_flavor_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained('product_flavors')
                    ->nullOnDelete();
            }
        });

        $this->backfillProductFlavors();
        $this->backfillSelectedFlavorIds('cart_items');
        $this->backfillSelectedFlavorIds('order_items');
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'product_flavor_id')) {
                $table->dropConstrainedForeignId('product_flavor_id');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'product_flavor_id')) {
                $table->dropConstrainedForeignId('product_flavor_id');
            }
        });

        Schema::dropIfExists('product_flavors');
    }

    private function backfillProductFlavors(): void
    {
        if (!Schema::hasTable('product_flavors') || !Schema::hasTable('products')) {
            return;
        }

        $hasFlavor = Schema::hasColumn('products', 'flavor');
        $hasReorderLevel = Schema::hasColumn('products', 'reorder_level');
        $now = now();

        DB::table('products')
            ->orderBy('id')
            ->get()
            ->each(function ($product) use ($hasFlavor, $hasReorderLevel, $now): void {
                $existing = DB::table('product_flavors')
                    ->where('product_id', $product->id)
                    ->exists();

                if ($existing) {
                    return;
                }

                $name = $hasFlavor ? trim((string) ($product->flavor ?? '')) : '';
                $name = $name !== '' ? $name : 'Original';

                DB::table('product_flavors')->insert([
                    'product_id' => $product->id,
                    'name' => $name,
                    'stock' => max(0, (int) ($product->stock ?? 0)),
                    'reorder_level' => $hasReorderLevel ? max(0, (int) ($product->reorder_level ?? 5)) : 5,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            });
    }

    private function backfillSelectedFlavorIds(string $table): void
    {
        if (
            !Schema::hasTable($table)
            || !Schema::hasColumn($table, 'product_flavor_id')
            || !Schema::hasColumn($table, 'selected_flavor')
        ) {
            return;
        }

        DB::table($table)
            ->whereNull('product_flavor_id')
            ->orderBy('id')
            ->get()
            ->each(function ($item) use ($table): void {
                $selectedFlavor = trim((string) ($item->selected_flavor ?? ''));

                $flavorQuery = DB::table('product_flavors')
                    ->where('product_id', $item->product_id);

                if ($selectedFlavor !== '') {
                    $flavorQuery->whereRaw('LOWER(name) = ?', [mb_strtolower($selectedFlavor)]);
                }

                $flavor = $flavorQuery->orderByDesc('is_active')->orderBy('id')->first();

                if (!$flavor) {
                    return;
                }

                DB::table($table)
                    ->where('id', $item->id)
                    ->update([
                        'product_flavor_id' => $flavor->id,
                        'selected_flavor' => $flavor->name,
                        'updated_at' => now(),
                    ]);
            });
    }
};
