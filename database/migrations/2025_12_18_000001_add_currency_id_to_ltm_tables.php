<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ltm_auctions', function (Blueprint $table) {
            $table->foreignId('currency_id')
                ->nullable()
                ->after('estimated_value_eur')
                ->constrained('currencies')
                ->restrictOnDelete();
        });

        Schema::table('ltm_lots', function (Blueprint $table) {
            $table->foreignId('currency_id')
                ->nullable()
                ->after('max_budget_eur')
                ->constrained('currencies')
                ->restrictOnDelete();
        });

        Schema::table('ltm_bids', function (Blueprint $table) {
            $table->foreignId('currency_id')
                ->nullable()
                ->after('price_per_ton_eur')
                ->constrained('currencies')
                ->restrictOnDelete();
        });

        Schema::table('ltm_contracts', function (Blueprint $table) {
            $table->foreignId('currency_id')
                ->nullable()
                ->after('average_price_per_trip_eur')
                ->constrained('currencies')
                ->restrictOnDelete();
        });

        $eurId = DB::table('currencies')->where('code', 'EUR')->value('id');
        if (!$eurId) {
            $eurId = DB::table('currencies')->insertGetId([
                'code' => 'EUR',
                'name' => 'Euro',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasColumn('ltm_auctions', 'currency')) {
            $currencyIdsByCode = DB::table('currencies')->pluck('id', 'code');
            foreach ($currencyIdsByCode as $code => $id) {
                DB::table('ltm_auctions')->where('currency', $code)->update(['currency_id' => $id]);
            }
        }

        DB::table('ltm_auctions')->whereNull('currency_id')->update(['currency_id' => $eurId]);
        DB::table('ltm_lots')->whereNull('currency_id')->update(['currency_id' => $eurId]);
        DB::table('ltm_bids')->whereNull('currency_id')->update(['currency_id' => $eurId]);
        DB::table('ltm_contracts')->whereNull('currency_id')->update(['currency_id' => $eurId]);

        Schema::table('ltm_auctions', function (Blueprint $table) {
            if (Schema::hasColumn('ltm_auctions', 'currency')) {
                $table->dropColumn('currency');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ltm_auctions', function (Blueprint $table) {
            $table->string('currency')->nullable()->default('EUR')->after('estimated_value_eur');
        });

        Schema::table('ltm_auctions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
        Schema::table('ltm_lots', function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
        Schema::table('ltm_bids', function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
        Schema::table('ltm_contracts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
        });
    }
};

