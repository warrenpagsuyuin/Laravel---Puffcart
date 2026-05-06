<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'mfa_enabled')) {
                $table->boolean('mfa_enabled')->default(false);
            }
            if (!Schema::hasColumn('users', 'locked_until')) {
                $table->timestamp('locked_until')->nullable();
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'mfa_enabled')) {
                $table->dropColumn('mfa_enabled');
            }
            if (Schema::hasColumn('users', 'locked_until')) {
                $table->dropColumn('locked_until');
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};
