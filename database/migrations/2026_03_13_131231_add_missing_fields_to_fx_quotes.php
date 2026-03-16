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
            if (!Schema::hasColumn('fx_quotes', 'send_amount')) {
                $table->decimal('send_amount', 12, 4)->after('chf_amount')->nullable();
            }
            if (!Schema::hasColumn('fx_quotes', 'target_amount')) {
                $table->decimal('target_amount', 14, 4)->after('send_amount')->nullable();
            }
            if (!Schema::hasColumn('fx_quotes', 'agent_commission')) {
                $table->decimal('agent_commission', 10, 4)->after('fee')->default(0);
            }
            if (!Schema::hasColumn('fx_quotes', 'admin_commission')) {
                $table->decimal('admin_commission', 10, 4)->after('agent_commission')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fx_quotes', function (Blueprint $table) {
            $table->dropColumn(['send_amount', 'target_amount', 'agent_commission', 'admin_commission']);
        });
    }
};
