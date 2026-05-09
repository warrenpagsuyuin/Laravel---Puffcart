<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_flavors', function (Blueprint $table) {
            if (!Schema::hasColumn('product_flavors', 'option_type')) {
                $table->string('option_type', 20)->default('flavor')->after('name')->index();
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'battery_color_id')) {
                $table->foreignId('battery_color_id')
                    ->nullable()
                    ->after('product_flavor_id')
                    ->constrained('product_flavors')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('cart_items', 'selected_battery_color')) {
                $table->string('selected_battery_color')->nullable()->after('selected_flavor');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'battery_color_id')) {
                $table->foreignId('battery_color_id')
                    ->nullable()
                    ->after('product_flavor_id')
                    ->constrained('product_flavors')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('order_items', 'selected_battery_color')) {
                $table->string('selected_battery_color')->nullable()->after('selected_flavor');
            }
        });

        $this->backfillOptionTypes();
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'battery_color_id')) {
                $table->dropConstrainedForeignId('battery_color_id');
            }

            if (Schema::hasColumn('order_items', 'selected_battery_color')) {
                $table->dropColumn('selected_battery_color');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'battery_color_id')) {
                $table->dropConstrainedForeignId('battery_color_id');
            }

            if (Schema::hasColumn('cart_items', 'selected_battery_color')) {
                $table->dropColumn('selected_battery_color');
            }
        });

        Schema::table('product_flavors', function (Blueprint $table) {
            if (Schema::hasColumn('product_flavors', 'option_type')) {
                $table->dropColumn('option_type');
            }
        });
    }

    private function backfillOptionTypes(): void
    {
        if (!Schema::hasTable('products') || !Schema::hasTable('product_flavors')) {
            return;
        }

        $colorNames = collect([
            'BLACK',
            'WHITE',
            'SILVER',
            'GOLD',
            'ROSE GOLD',
            'RED',
            'BLUE',
            'GREEN',
            'PINK',
            'PURPLE',
            'GREY',
            'GRAY',
            'TITANIUM GREY',
            'TITANIUM GRAY',
            'ORIGINAL',
        ]);

        DB::table('product_flavors')
            ->join('products', 'product_flavors.product_id', '=', 'products.id')
            ->select('product_flavors.id', 'product_flavors.name', 'products.product_type')
            ->orderBy('product_flavors.id')
            ->get()
            ->each(function ($row) use ($colorNames): void {
                $name = mb_strtoupper(trim((string) $row->name));
                $type = match ($row->product_type) {
                    Product::TYPE_BATTERY => 'color',
                    Product::TYPE_BUNDLE => $colorNames->contains($name) ? 'color' : 'flavor',
                    default => 'flavor',
                };

                DB::table('product_flavors')
                    ->where('id', $row->id)
                    ->update(['option_type' => $type]);
            });
    }
};
