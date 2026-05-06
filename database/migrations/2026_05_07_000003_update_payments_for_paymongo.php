<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add PayMongo payment fields if they don't exist
            if (!Schema::hasColumn('payments', 'paymongo_checkout_id')) {
                $table->string('paymongo_checkout_id')->nullable();
            }
            if (!Schema::hasColumn('payments', 'paymongo_payment_intent_id')) {
                $table->string('paymongo_payment_intent_id')->nullable();
            }
            if (!Schema::hasColumn('payments', 'paymongo_payment_id')) {
                $table->string('paymongo_payment_id')->nullable();
            }
            if (!Schema::hasColumn('payments', 'payment_status')) {
                $table->string('payment_status')->default('pending'); // pending, paid, failed, expired, cancelled, refunded
            }
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable(); // e.g., gcash, card, bank_transfer
            }
            if (!Schema::hasColumn('payments', 'currency')) {
                $table->string('currency')->default('PHP');
            }
            if (!Schema::hasColumn('payments', 'transaction_reference')) {
                $table->string('transaction_reference')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $columns = [
                'paymongo_checkout_id',
                'paymongo_payment_intent_id',
                'paymongo_payment_id',
                'payment_status',
                'payment_method',
                'currency',
                'transaction_reference',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
