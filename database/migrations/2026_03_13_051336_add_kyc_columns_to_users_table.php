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
        Schema::table('users', function (Blueprint $table) {
            $table->string('sumsub_applicant_id')->nullable()->after('status');
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending')->after('sumsub_applicant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sumsub_applicant_id', 'kyc_status']);
        });
    }
};
