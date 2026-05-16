<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_name')) {
                $table->string('product_name')->nullable()->after('id')->index();
            }

            if (!Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable()->after('category');
            }

            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('category')->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('products', 'product_type')) {
                $table->string('product_type')->default('other')->after('brand')->index();
            }

            if (!Schema::hasColumn('products', 'original_price')) {
                $table->decimal('original_price', 10, 2)->nullable()->after('price');
            }

            if (!Schema::hasColumn('products', 'reorder_level')) {
                $table->unsignedInteger('reorder_level')->default(5)->after('stock');
            }

            if (!Schema::hasColumn('products', 'badge')) {
                $table->string('badge')->default('none')->after('image');
            }

            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('stock');
            }
        });

        DB::table('products')
            ->whereNull('product_name')
            ->update(['product_name' => DB::raw('name')]);

        Schema::table('product_flavors', function (Blueprint $table) {
            if (!Schema::hasColumn('product_flavors', 'flavor')) {
                $table->string('flavor')->nullable()->after('product_id')->index();
            }

            if (!Schema::hasColumn('product_flavors', 'stock')) {
                $table->unsignedInteger('stock')->default(0);
            }

            if (!Schema::hasColumn('product_flavors', 'reorder_level')) {
                $table->unsignedInteger('reorder_level')->default(5);
            }
        });

        DB::table('product_flavors')
            ->whereNull('flavor')
            ->update(['flavor' => DB::raw('name')]);

        if (!Schema::hasTable('inventory_logs')) {
            Schema::create('inventory_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('product_flavor_id')->nullable()->constrained('product_flavors')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('event');
                $table->integer('quantity_before')->nullable();
                $table->integer('quantity_after')->nullable();
                $table->integer('quantity_delta')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['product_id', 'event']);
                $table->index(['product_flavor_id', 'event']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');

        Schema::table('product_flavors', function (Blueprint $table) {
            if (Schema::hasColumn('product_flavors', 'flavor')) {
                $table->dropColumn('flavor');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'product_name')) {
                $table->dropColumn('product_name');
            }
        });
    }
};
