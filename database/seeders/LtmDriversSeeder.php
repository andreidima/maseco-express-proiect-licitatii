<?php

namespace Database\Seeders;

use App\Models\Ltm\Carrier;
use App\Models\Ltm\Driver;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LtmDriversSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');
        $carriers = Carrier::pluck('id')->all();

        $languageCombos = [
            'română, engleză',
            'română, franceză',
            'română, germană',
            'română, maghiară',
            'română, italiană',
        ];

        for ($i = 1; $i <= 85; $i++) {
            Driver::create([
                'carrier_id' => $faker->randomElement($carriers),
                'name' => $faker->name(),
                'phone' => $faker->phoneNumber(),
                'email' => $faker->safeEmail(),
                'languages' => $faker->randomElement($languageCombos),
                'experience_years' => $faker->numberBetween(1, 25),
                'has_adr' => $faker->randomElement(['da', 'nu']),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
