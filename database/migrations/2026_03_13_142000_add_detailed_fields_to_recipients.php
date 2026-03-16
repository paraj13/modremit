<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipients', function (Blueprint $table) {
            // Add Address fields (Required for USD, PHP, etc.)
            if (!Schema::hasColumn('recipients', 'address_line_1')) {
                $table->string('address_line_1')->nullable()->after('upi_id');
            }
            if (!Schema::hasColumn('recipients', 'city')) {
                $table->string('city')->nullable()->after('address_line_1');
            }
            if (!Schema::hasColumn('recipients', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('city');
            }
            if (!Schema::hasColumn('recipients', 'state')) {
                $table->string('state')->nullable()->after('postal_code');
            }

            // Add missing Bank fields
            if (!Schema::hasColumn('recipients', 'iban')) {
                $table->string('iban')->nullable()->after('state');
            }
            if (!Schema::hasColumn('recipients', 'swift_code')) {
                $table->string('swift_code')->nullable()->after('iban');
            }
            if (!Schema::hasColumn('recipients', 'routing_number')) {
                $table->string('routing_number')->nullable()->after('swift_code');
            }
            if (!Schema::hasColumn('recipients', 'sort_code')) {
                $table->string('sort_code')->nullable()->after('routing_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('recipients', function (Blueprint $table) {
            $table->dropColumn([
                'address_line_1', 'city', 'postal_code', 'state',
                'iban', 'swift_code', 'routing_number', 'sort_code'
            ]);
        });
    }
};
