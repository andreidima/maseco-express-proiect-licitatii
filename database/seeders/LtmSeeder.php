<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LtmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CurrencySeeder::class,
            LtmClientsSeeder::class,
            LtmCarriersSeeder::class,
            LtmRoutesSeeder::class,
            LtmAuctionsSeeder::class,
            LtmLotsSeeder::class,
            LtmBidsSeeder::class,
            LtmContractsSeeder::class,
            LtmTrucksSeeder::class,
            LtmDriversSeeder::class,
            LtmDocumentsSeeder::class,
        ]);
    }
}
