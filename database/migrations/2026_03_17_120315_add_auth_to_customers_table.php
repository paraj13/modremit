<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Make agent_id nullable (self-registered customers won't have an agent)
            $table->foreignId('agent_id')->nullable()->change();
            // Auth columns
            $table->string('password')->nullable()->after('email');
            $table->string('remember_token', 100)->nullable()->after('password');
            $table->timestamp('email_verified_at')->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('agent_id')->nullable(false)->change();
            $table->dropColumn(['password', 'remember_token', 'email_verified_at']);
        });
    }
};
