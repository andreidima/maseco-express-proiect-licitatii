<?php

namespace Database\Seeders;

use App\Models\Ltm\Auction;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Client;
use App\Models\Ltm\Contract;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LtmContractsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $eurId = DB::table('currencies')->where('code', 'EUR')->value('id');

        $auctions = Auction::all();
        $carriers = Carrier::pluck('id')->all();
        $clients = Client::pluck('id')->all();
        $statuses = ['activ', 'expirat', 'reziliat'];
        $types = ['cadru', 'spot'];

        for ($i = 1; $i <= 70; $i++) {
            $auction = $auctions->random();
            $carrierId = $faker->randomElement($carriers);
            $clientId = $auction->client_id ?: $faker->randomElement($clients);

            $validFrom = Carbon::now()->subMonths($faker->numberBetween(0, 6))->addDays($faker->numberBetween(0, 15));
            $validTo = (clone $validFrom)->addMonths($faker->numberBetween(3, 18));

            Contract::create([
                'auction_id' => $auction->id,
                'carrier_id' => $carrierId,
                'client_id' => $clientId,
                'contract_number' => 'CTR-LTM-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'contract_type' => $faker->randomElement($types),
                'total_value_eur' => $faker->numberBetween(20000, 500000),
                'average_price_per_trip_eur' => $faker->randomFloat(2, 400, 5000),
                'currency_id' => $eurId,
                'valid_from' => $validFrom->toDateString(),
                'valid_to' => $validTo->toDateString(),
                'status' => $faker->randomElement($statuses),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
