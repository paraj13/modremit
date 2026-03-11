<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fx_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('quote_id')->nullable(); // Revolut quote ID
            $table->decimal('chf_amount', 12, 4);
            $table->decimal('inr_amount', 14, 4);
            $table->decimal('rate', 12, 6);
            $table->decimal('fee', 10, 4)->default(0);
            $table->string('from_currency', 3)->default('CHF');
            $table->string('to_currency', 3)->default('INR');
            $table->timestamp('expires_at')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fx_quotes');
    }
};
