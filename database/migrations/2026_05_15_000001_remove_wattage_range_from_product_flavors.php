<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_flavors', function (Blueprint $table) {
            if (Schema::hasColumn('product_flavors', 'wattage_range')) {
                $table->dropColumn('wattage_range');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_flavors', function (Blueprint $table) {
            if (!Schema::hasColumn('product_flavors', 'wattage_range')) {
                $table->string('wattage_range', 60)->nullable()->after('name');
            }
        });
    }
};
