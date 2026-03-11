<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->text('address')->nullable();
            $table->enum('kyc_status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->string('sumsub_applicant_id')->nullable();
            $table->json('kyc_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
