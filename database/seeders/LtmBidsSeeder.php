<?php

namespace Database\Seeders;

use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Lot;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LtmBidsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $eurId = DB::table('currencies')->where('code', 'EUR')->value('id');

        $carriers = Carrier::pluck('id')->all();
        $lots = Lot::all();
        $auctions = Auction::pluck('id')->all();
        $statuses = ['în analiză', 'acceptată', 'respinsă'];

        for ($i = 1; $i <= 100; $i++) {
            $lot = $lots->random();
            $auctionId = $lot->auction_id ?: $faker->randomElement($auctions);

            Bid::create([
                'auction_id' => $auctionId,
                'lot_id' => $lot->id,
                'carrier_id' => $faker->randomElement($carriers),
                'price_per_trip_eur' => $faker->randomFloat(2, 350, 4500),
                'price_per_ton_eur' => $faker->randomFloat(2, 25, 250),
                'currency_id' => $eurId,
                'surcharge_fuel_percent' => $faker->randomFloat(2, 0, 15),
                'payment_terms_days' => $faker->numberBetween(7, 45),
                'status' => $faker->randomElement($statuses),
                'internal_comment' => $faker->optional()->sentence(),
            ]);
        }
    }
}
