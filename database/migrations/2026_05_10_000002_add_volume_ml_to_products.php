<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        if (!Schema::hasColumn('products', 'volume_ml')) {
            Schema::table('products', function (Blueprint $table) {
                $column = $table->unsignedSmallInteger('volume_ml')->nullable();

                if (Schema::hasColumn('products', 'nicotine_strengths')) {
                    $column->after('nicotine_strengths');
                }
            });
        }

        DB::table('products')
            ->whereNull('volume_ml')
            ->orderBy('id')
            ->get(['id', 'name', 'sku', 'description'])
            ->each(function ($product): void {
                $text = collect([
                    $product->name ?? '',
                    $product->sku ?? '',
                    $product->description ?? '',
                ])->implode(' ');

                if (preg_match('/(\d{1,4})\s*ml\b/i', $text, $matches) !== 1) {
                    return;
                }

                $volume = (int) $matches[1];

                if ($volume < 1 || $volume > 1000) {
                    return;
                }

                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['volume_ml' => $volume]);
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('products') || !Schema::hasColumn('products', 'volume_ml')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('volume_ml');
        });
    }
};
