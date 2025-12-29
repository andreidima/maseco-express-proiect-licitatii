<?php

namespace Database\Seeders;

use App\Models\Ltm\Carrier;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LtmCarriersSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $cities = ['București', 'Cluj-Napoca', 'Brașov', 'Timișoara', 'Iași', 'Constanța', 'Oradea', 'Sibiu', 'Arad', 'Craiova', 'Galați', 'Buzău'];
        $countries = ['România', 'Germania', 'Austria', 'Franța', 'Ungaria', 'Polonia', 'Italia', 'Bulgaria'];

        for ($i = 1; $i <= 65; $i++) {
            Carrier::create([
                'name' => 'SC ' . $faker->company() . ' Transport SRL',
                'cui' => 'RO' . $faker->numerify('########'),
                'registration_number' => 'J' . $faker->numerify('##/####/####'),
                'contact_person' => $faker->name(),
                'phone' => $faker->phoneNumber(),
                'email' => $faker->companyEmail(),
                'city' => $faker->randomElement($cities),
                'country' => $faker->randomElement($countries),
                'rating' => $faker->randomFloat(2, 2.5, 5),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
