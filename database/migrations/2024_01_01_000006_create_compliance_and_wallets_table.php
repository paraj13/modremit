<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('compliance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'cleared', 'escalated'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('chf_balance', 14, 4)->default(0);
            $table->decimal('total_received', 14, 4)->default(0);
            $table->decimal('total_sent_inr', 16, 4)->default(0);
            $table->decimal('total_commission', 12, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('compliance_logs');
    }
};
