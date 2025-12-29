<?php

namespace Database\Seeders;

use App\Models\Ltm\Carrier;
use App\Models\Ltm\Truck;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LtmTrucksSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');
        $carriers = Carrier::pluck('id')->all();

        $types = ['prelată', 'frig', 'cisternă', 'duba', 'platformă'];

        for ($i = 1; $i <= 90; $i++) {
            Truck::create([
                'carrier_id' => $faker->randomElement($carriers),
                'plate_number' => strtoupper($faker->bothify('??-##-###')),
                'truck_type' => $faker->randomElement($types),
                'max_weight_tons' => $faker->randomFloat(2, 3.5, 24),
                'euro_class' => 'Euro ' . $faker->randomElement([4, 5, 6]),
                'has_adr' => $faker->randomElement(['da', 'nu']),
                'notes' => $faker->optional()->sentence(),
            ]);
        }
    }
}
