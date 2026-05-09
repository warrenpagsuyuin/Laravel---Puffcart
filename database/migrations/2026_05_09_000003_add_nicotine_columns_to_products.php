<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'nicotine_type') || !Schema::hasColumn('products', 'nicotine_strengths')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'nicotine_type')) {
                    $table->string('nicotine_type')->nullable()->after('product_type');
                }

                if (!Schema::hasColumn('products', 'nicotine_strengths')) {
                    $table->json('nicotine_strengths')->nullable()->after('nicotine_type');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'nicotine_strengths')) {
                $table->dropColumn('nicotine_strengths');
            }

            if (Schema::hasColumn('products', 'nicotine_type')) {
                $table->dropColumn('nicotine_type');
            }
        });
    }
};
