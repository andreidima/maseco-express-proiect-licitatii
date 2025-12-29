<?php

namespace Database\Seeders;

use App\Models\Ltm\Auction;
use App\Models\Ltm\Client;
use App\Models\Ltm\Route;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LtmAuctionsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $eurId = DB::table('currencies')->where('code', 'EUR')->value('id');

        $clients = Client::pluck('id')->all();
        $routes = Route::pluck('id')->all();

        $types = ['licitație spot', 'contract anual', 'mini licitație', 'tender trimestrial'];
        $statuses = ['în pregătire', 'deschisă', 'în evaluare', 'atribuită', 'anulată'];

        for ($i = 1; $i <= 65; $i++) {
            $clientId = $faker->randomElement($clients);
            $routeId = $faker->randomElement($routes);

            Auction::create([
                'auction_number' => 'LTM-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'title' => 'Licitație transport ' . $faker->randomElement(['mărfuri generale', 'mărfuri paletizate', 'refrigerate', 'materiale industriale']),
                'description' => $faker->sentence(12),
                'client_id' => $clientId,
                'route_id' => $routeId,
                'type' => $faker->randomElement($types),
                'status' => $faker->randomElement($statuses),
                'estimated_value_eur' => $faker->numberBetween(20000, 400000),
                'currency_id' => $eurId,
                'total_lots' => $faker->numberBetween(1, 8),
                'expected_volume_tons' => $faker->randomFloat(2, 40, 400),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
