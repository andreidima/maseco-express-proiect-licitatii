<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'carrier_id')) {
                $table
                    ->foreignId('carrier_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('ltm_carriers')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'carrier_id')) {
                $table->dropConstrainedForeignId('carrier_id');
            }
        });
    }
};

