<?php

namespace Database\Seeders;

use App\Models\Ltm\Route;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LtmRoutesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $cities = ['București', 'Cluj-Napoca', 'Brașov', 'Timișoara', 'Iași', 'Constanța', 'Oradea', 'Sibiu', 'Arad', 'Ploiești', 'Suceava', 'Baia Mare', 'Budapesta', 'Vienna', 'Praga', 'Milano', 'Berlin', 'Varșovia'];
        $countries = ['România', 'Germania', 'Austria', 'Franța', 'Ungaria', 'Polonia', 'Italia', 'Cehia', 'Bulgaria'];
        $goods = ['marfă paletizată', 'produse alimentare', 'materiale de construcții', 'echipamente industriale', 'electronice', 'textile', 'mobilier'];

        for ($i = 1; $i <= 60; $i++) {
            Route::create([
                'code' => 'CURS-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'origin_city' => $faker->randomElement($cities),
                'origin_country' => $faker->randomElement($countries),
                'destination_city' => $faker->randomElement($cities),
                'destination_country' => $faker->randomElement($countries),
                'distance_km' => $faker->numberBetween(150, 1800),
                'typical_goods' => $faker->randomElement($goods),
                'average_weight_tons' => $faker->randomFloat(2, 8, 24),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
