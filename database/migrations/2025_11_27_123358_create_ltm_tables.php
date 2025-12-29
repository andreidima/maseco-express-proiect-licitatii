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
        Schema::create('ltm_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('cui')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->integer('payment_terms_days')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_carriers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('cui')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_routes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('origin_city')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('destination_city')->nullable();
            $table->string('destination_country')->nullable();
            $table->integer('distance_km')->nullable();
            $table->string('typical_goods')->nullable();
            $table->decimal('average_weight_tons', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_auctions', function (Blueprint $table) {
            $table->id();
            $table->string('auction_number')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('ltm_clients')->nullOnDelete();
            $table->foreignId('route_id')->nullable()->constrained('ltm_routes')->nullOnDelete();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->decimal('estimated_value_eur', 15, 2)->nullable();
            $table->string('currency')->nullable()->default('EUR');
            $table->integer('total_lots')->nullable();
            $table->decimal('expected_volume_tons', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->nullable()->constrained('ltm_auctions')->nullOnDelete();
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->string('goods_type')->nullable();
            $table->decimal('weight_tons', 15, 2)->nullable();
            $table->integer('pallets')->nullable();
            $table->integer('trips_per_month')->nullable();
            $table->decimal('max_budget_eur', 15, 2)->nullable();
            $table->string('pickup_city')->nullable();
            $table->string('pickup_country')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_country')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->nullable()->constrained('ltm_auctions')->nullOnDelete();
            $table->foreignId('lot_id')->nullable()->constrained('ltm_lots')->nullOnDelete();
            $table->foreignId('carrier_id')->nullable()->constrained('ltm_carriers')->nullOnDelete();
            $table->decimal('price_per_trip_eur', 15, 2)->nullable();
            $table->decimal('price_per_ton_eur', 15, 2)->nullable();
            $table->decimal('surcharge_fuel_percent', 5, 2)->nullable();
            $table->integer('payment_terms_days')->nullable();
            $table->string('status')->nullable();
            $table->text('internal_comment')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->nullable()->constrained('ltm_auctions')->nullOnDelete();
            $table->foreignId('carrier_id')->nullable()->constrained('ltm_carriers')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('ltm_clients')->nullOnDelete();
            $table->string('contract_number')->nullable();
            $table->string('contract_type')->nullable();
            $table->decimal('total_value_eur', 15, 2)->nullable();
            $table->decimal('average_price_per_trip_eur', 15, 2)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrier_id')->nullable()->constrained('ltm_carriers')->nullOnDelete();
            $table->string('plate_number')->nullable();
            $table->string('truck_type')->nullable();
            $table->decimal('max_weight_tons', 10, 2)->nullable();
            $table->string('euro_class')->nullable();
            $table->string('has_adr')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrier_id')->nullable()->constrained('ltm_carriers')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('languages')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('has_adr')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ltm_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()->constrained('ltm_contracts')->nullOnDelete();
            $table->foreignId('auction_id')->nullable()->constrained('ltm_auctions')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('ltm_clients')->nullOnDelete();
            $table->foreignId('carrier_id')->nullable()->constrained('ltm_carriers')->nullOnDelete();
            $table->string('type')->nullable();
            $table->string('file_path')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ltm_documents');
        Schema::dropIfExists('ltm_drivers');
        Schema::dropIfExists('ltm_trucks');
        Schema::dropIfExists('ltm_contracts');
        Schema::dropIfExists('ltm_bids');
        Schema::dropIfExists('ltm_lots');
        Schema::dropIfExists('ltm_auctions');
        Schema::dropIfExists('ltm_routes');
        Schema::dropIfExists('ltm_carriers');
        Schema::dropIfExists('ltm_clients');
    }
};
