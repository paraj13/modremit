<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('recipient_id')->constrained('recipients');
            $table->foreignId('fx_quote_id')->nullable()->constrained('fx_quotes')->nullOnDelete();
            $table->decimal('chf_amount', 12, 4);
            $table->decimal('inr_amount', 14, 4);
            $table->decimal('commission', 10, 4)->default(0);
            $table->decimal('rate', 12, 6);
            $table->string('payment_reference')->nullable();
            $table->string('revolut_payment_id')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->boolean('flagged')->default(false);
            $table->text('notes')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
