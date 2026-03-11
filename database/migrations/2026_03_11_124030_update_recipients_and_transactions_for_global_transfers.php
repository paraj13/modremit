<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recipients', function (Blueprint $table) {
            if (!Schema::hasColumn('recipients', 'iban')) $table->string('iban')->nullable()->after('account_number');
            if (!Schema::hasColumn('recipients', 'swift_code')) $table->string('swift_code')->nullable()->after('iban');
            if (!Schema::hasColumn('recipients', 'routing_number')) $table->string('routing_number')->nullable()->after('swift_code');
            if (!Schema::hasColumn('recipients', 'sort_code')) $table->string('sort_code')->nullable()->after('routing_number');
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'target_currency')) $table->string('target_currency', 3)->default('INR')->after('fx_quote_id');
            if (Schema::hasColumn('transactions', 'inr_amount') && !Schema::hasColumn('transactions', 'target_amount')) $table->renameColumn('inr_amount', 'target_amount');
            if (Schema::hasColumn('transactions', 'payment_reference') && !Schema::hasColumn('transactions', 'payment_ref')) $table->renameColumn('payment_reference', 'payment_ref');
        });

        Schema::table('fx_quotes', function (Blueprint $table) {
            if (Schema::hasColumn('fx_quotes', 'inr_amount') && !Schema::hasColumn('fx_quotes', 'target_amount')) $table->renameColumn('inr_amount', 'target_amount');
        });
    }

    public function down(): void
    {
        Schema::table('fx_quotes', function (Blueprint $table) {
            if (Schema::hasColumn('fx_quotes', 'target_amount')) $table->renameColumn('target_amount', 'inr_amount');
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'target_amount')) $table->renameColumn('target_amount', 'inr_amount');
            if (Schema::hasColumn('transactions', 'payment_ref')) $table->renameColumn('payment_ref', 'payment_reference');
            if (Schema::hasColumn('transactions', 'target_currency')) $table->dropColumn('target_currency');
        });

        Schema::table('recipients', function (Blueprint $table) {
            $table->dropColumn(['iban', 'swift_code', 'routing_number', 'sort_code']);
        });
    }
};
