<?php

namespace Database\Seeders;

use App\Models\Ltm\Auction;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Client;
use App\Models\Ltm\Contract;
use App\Models\Ltm\Document;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LtmDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $contracts = Contract::all();
        $auctions = Auction::pluck('id')->all();
        $clients = Client::pluck('id')->all();
        $carriers = Carrier::pluck('id')->all();

        $types = ['ofertă semnată', 'contract', 'cmr', 'factură', 'proces verbal', 'polita asigurare'];

        for ($i = 1; $i <= 70; $i++) {
            $contract = $contracts->random();

            Document::create([
                'contract_id' => $contract->id,
                'auction_id' => $contract->auction_id ?: $faker->randomElement($auctions),
                'client_id' => $contract->client_id ?: $faker->randomElement($clients),
                'carrier_id' => $contract->carrier_id ?: $faker->randomElement($carriers),
                'type' => $faker->randomElement($types),
                'file_path' => 'documente/ltm_' . $i . '.pdf',
                'description' => $faker->sentence(10),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
