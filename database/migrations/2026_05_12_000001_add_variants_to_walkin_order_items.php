<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('walkin_order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('walkin_order_items', 'product_flavor_id')) {
                $table->foreignId('product_flavor_id')->nullable()->after('product_id')->constrained('product_flavors')->nullOnDelete();
            }

            if (!Schema::hasColumn('walkin_order_items', 'battery_color_id')) {
                $table->foreignId('battery_color_id')->nullable()->after('product_flavor_id')->constrained('product_flavors')->nullOnDelete();
            }

            if (!Schema::hasColumn('walkin_order_items', 'selected_flavor')) {
                $table->string('selected_flavor')->nullable()->after('quantity');
            }

            if (!Schema::hasColumn('walkin_order_items', 'selected_battery_color')) {
                $table->string('selected_battery_color')->nullable()->after('selected_flavor');
            }

            if (!Schema::hasColumn('walkin_order_items', 'product_type')) {
                $table->string('product_type', 30)->nullable()->after('selected_battery_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('walkin_order_items', function (Blueprint $table) {
            foreach (['product_type', 'selected_battery_color', 'selected_flavor'] as $column) {
                if (Schema::hasColumn('walkin_order_items', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('walkin_order_items', 'battery_color_id')) {
                $table->dropConstrainedForeignId('battery_color_id');
            }

            if (Schema::hasColumn('walkin_order_items', 'product_flavor_id')) {
                $table->dropConstrainedForeignId('product_flavor_id');
            }
        });
    }
};
