<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique();
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer');
            }

            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }

            if (!Schema::hasColumn('users', 'valid_id_path')) {
                $table->string('valid_id_path')->nullable();
            }

            if (!Schema::hasColumn('users', 'age_confirmed')) {
                $table->boolean('age_confirmed')->default(false);
            }

            if (!Schema::hasColumn('users', 'privacy_consent')) {
                $table->boolean('privacy_consent')->default(false);
            }

            if (!Schema::hasColumn('users', 'verification_status')) {
                $table->string('verification_status')->default('pending');
            }

            if (!Schema::hasColumn('users', 'verification_reviewed_at')) {
                $table->timestamp('verification_reviewed_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'failed_login_attempts')) {
                $table->unsignedInteger('failed_login_attempts')->default(0);
            }

            if (!Schema::hasColumn('users', 'last_failed_login_at')) {
                $table->timestamp('last_failed_login_at')->nullable();
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->unique();
            }

            if (!Schema::hasColumn('products', 'original_price')) {
                $table->decimal('original_price', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable();
            }

            if (!Schema::hasColumn('products', 'badge')) {
                $table->string('badge')->default('none');
            }

            if (!Schema::hasColumn('products', 'reorder_level')) {
                $table->unsignedInteger('reorder_level')->default(5);
            }

            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }

            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (!Schema::hasColumn('products', 'rating')) {
                $table->decimal('rating', 3, 1)->default(0);
            }
        });

        DB::table('users')->whereNull('role')->update(['role' => 'customer']);

        if (Schema::hasColumn('users', 'verification_status')) {
            DB::table('users')
                ->whereNull('verification_status')
                ->update(['verification_status' => 'pending']);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach (['sku', 'original_price', 'image', 'badge', 'reorder_level', 'is_featured', 'is_active', 'rating'] as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'username',
                'date_of_birth',
                'valid_id_path',
                'age_confirmed',
                'privacy_consent',
                'verification_status',
                'verification_reviewed_at',
                'failed_login_attempts',
                'last_failed_login_at',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
