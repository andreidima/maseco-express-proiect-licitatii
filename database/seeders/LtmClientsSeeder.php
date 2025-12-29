<?php

namespace Database\Seeders;

use App\Models\Ltm\Client;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LtmClientsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $cities = ['București', 'Cluj-Napoca', 'Brașov', 'Timișoara', 'Iași', 'Constanța', 'Oradea', 'Sibiu', 'Arad', 'Pitești', 'Paris', 'Berlin', 'Vienna', 'Budapesta', 'Varșovia'];
        $countries = ['România', 'Germania', 'Austria', 'Franța', 'Ungaria', 'Polonia', 'Italia', 'Bulgaria'];

        for ($i = 1; $i <= 70; $i++) {
            $name = sprintf(
                '%s %s %s',
                $faker->randomElement(['SC', 'SRL', 'Compania', 'Firma']),
                $faker->company(),
                'SRL'
            );

            Client::create([
                'name' => $name,
                'cui' => 'RO' . $faker->numerify('########'),
                'registration_number' => 'J' . $faker->numerify('##/####/####'),
                'contact_person' => $faker->name(),
                'phone' => $faker->phoneNumber(),
                'email' => $faker->companyEmail(),
                'city' => $faker->randomElement($cities),
                'country' => $faker->randomElement($countries),
                'payment_terms_days' => $faker->numberBetween(15, 60),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
