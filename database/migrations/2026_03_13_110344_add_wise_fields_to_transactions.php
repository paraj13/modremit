<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('wise_transfer_id')->nullable()->after('payment_ref');
            $table->string('wise_quote_id')->nullable()->after('wise_transfer_id');
            $table->string('wise_status')->nullable()->after('wise_quote_id');
            $table->json('wise_response')->nullable()->after('wise_status');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['wise_transfer_id', 'wise_quote_id', 'wise_status', 'wise_response']);
        });
    }
};
