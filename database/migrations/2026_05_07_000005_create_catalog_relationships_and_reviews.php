<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->string('icon')->nullable();
                $table->string('color')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable()->unique();
            }

            if (!Schema::hasColumn('products', 'tags')) {
                $table->json('tags')->nullable();
            }

            if (!Schema::hasColumn('products', 'sales_count')) {
                $table->unsignedInteger('sales_count')->default(0);
            }

            if (!Schema::hasColumn('products', 'views_count')) {
                $table->unsignedInteger('views_count')->default(0);
            }
        });

        if (!Schema::hasTable('product_reviews')) {
            Schema::create('product_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->boolean('is_approved')->default(true);
                $table->timestamps();

                $table->unique(['product_id', 'user_id']);
                $table->index(['product_id', 'is_approved']);
            });
        }

        $this->backfillCategories();
        $this->backfillProductSlugs();
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }

            foreach (['slug', 'tags', 'sales_count', 'views_count'] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::dropIfExists('categories');
    }

    private function backfillCategories(): void
    {
        if (!Schema::hasColumn('products', 'category')) {
            return;
        }

        DB::table('products')
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->each(function (string $name): void {
                DB::table('categories')->updateOrInsert(
                    ['slug' => Str::slug($name)],
                    [
                        'name' => $name,
                        'slug' => Str::slug($name),
                        'is_active' => true,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            });

        DB::table('categories')->orderBy('id')->get()->each(function ($category): void {
            DB::table('products')
                ->where('category', $category->name)
                ->whereNull('category_id')
                ->update(['category_id' => $category->id]);
        });
    }

    private function backfillProductSlugs(): void
    {
        DB::table('products')
            ->select('id', 'name', 'slug')
            ->orderBy('id')
            ->get()
            ->each(function ($product): void {
                if ($product->slug) {
                    return;
                }

                $baseSlug = Str::slug($product->name) ?: "product-{$product->id}";
                $slug = $baseSlug;
                $suffix = 2;

                while (
                    DB::table('products')
                        ->where('slug', $slug)
                        ->where('id', '!=', $product->id)
                        ->exists()
                ) {
                    $slug = "{$baseSlug}-{$suffix}";
                    $suffix++;
                }

                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['slug' => $slug]);
            });
    }
};
