<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_type')) {
                $table->string('product_type', 30)->default('other')->after('brand');
            }

            if (!Schema::hasColumn('products', 'flavor')) {
                $table->string('flavor')->nullable()->after('product_type');
            }

            if (!Schema::hasColumn('products', 'bundle_pods')) {
                $table->string('bundle_pods')->nullable()->after('flavor');
            }

            if (!Schema::hasColumn('products', 'bundle_battery')) {
                $table->string('bundle_battery')->nullable()->after('bundle_pods');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'selected_flavor')) {
                $table->string('selected_flavor')->nullable()->after('quantity');
            }

            if (!Schema::hasColumn('cart_items', 'product_type')) {
                $table->string('product_type', 30)->nullable()->after('selected_flavor');
            }

            if (!Schema::hasColumn('cart_items', 'bundle_pods')) {
                $table->string('bundle_pods')->nullable()->after('product_type');
            }

            if (!Schema::hasColumn('cart_items', 'bundle_battery')) {
                $table->string('bundle_battery')->nullable()->after('bundle_pods');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'selected_flavor')) {
                $table->string('selected_flavor')->nullable()->after('quantity');
            }

            if (!Schema::hasColumn('order_items', 'product_type')) {
                $table->string('product_type', 30)->nullable()->after('selected_flavor');
            }

            if (!Schema::hasColumn('order_items', 'bundle_pods')) {
                $table->string('bundle_pods')->nullable()->after('product_type');
            }

            if (!Schema::hasColumn('order_items', 'bundle_battery')) {
                $table->string('bundle_battery')->nullable()->after('bundle_pods');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            foreach (['bundle_battery', 'bundle_pods', 'product_type', 'selected_flavor'] as $column) {
                if (Schema::hasColumn('order_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            foreach (['bundle_battery', 'bundle_pods', 'product_type', 'selected_flavor'] as $column) {
                if (Schema::hasColumn('cart_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('products', function (Blueprint $table) {
            foreach (['bundle_battery', 'bundle_pods', 'flavor', 'product_type'] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
