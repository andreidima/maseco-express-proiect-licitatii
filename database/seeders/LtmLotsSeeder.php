<?php

namespace Database\Seeders;

use App\Models\Ltm\Auction;
use App\Models\Ltm\Lot;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LtmLotsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $eurId = DB::table('currencies')->where('code', 'EUR')->value('id');

        $auctions = Auction::pluck('id')->all();
        $cities = ['București', 'Cluj-Napoca', 'Brașov', 'Timișoara', 'Iași', 'Constanța', 'Oradea', 'Sibiu', 'Arad', 'Galați', 'Craiova', 'Budapesta', 'Vienna', 'Berlin'];
        $countries = ['România', 'Germania', 'Austria', 'Franța', 'Ungaria', 'Polonia', 'Italia', 'Bulgaria'];
        $goodsTypes = ['FMCG', 'materiale construcții', 'produse alimentare', 'echipamente industriale', 'marfă paletizată', 'textile'];

        for ($i = 1; $i <= 95; $i++) {
            $auctionId = $faker->randomElement($auctions);

            Lot::create([
                'auction_id' => $auctionId,
                'code' => 'LOT-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'description' => 'Lot ' . $faker->randomElement(['regional', 'național', 'internațional']) . ' - ' . $faker->city(),
                'goods_type' => $faker->randomElement($goodsTypes),
                'weight_tons' => $faker->randomFloat(2, 8, 28),
                'pallets' => $faker->numberBetween(10, 66),
                'trips_per_month' => $faker->numberBetween(2, 30),
                'max_budget_eur' => $faker->numberBetween(1500, 25000),
                'currency_id' => $eurId,
                'pickup_city' => $faker->randomElement($cities),
                'pickup_country' => $faker->randomElement($countries),
                'delivery_city' => $faker->randomElement($cities),
                'delivery_country' => $faker->randomElement($countries),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
