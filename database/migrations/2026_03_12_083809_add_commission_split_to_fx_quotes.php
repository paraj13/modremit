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
        Schema::table('fx_quotes', function (Blueprint $table) {
            $table->decimal('send_amount', 12, 4)->after('chf_amount')->nullable();
            $table->decimal('agent_commission', 10, 4)->after('fee')->default(0);
            $table->decimal('admin_commission', 10, 4)->after('agent_commission')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fx_quotes', function (Blueprint $table) {
            $table->dropColumn(['send_amount', 'agent_commission', 'admin_commission']);
        });
    }
};
