<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'EUR', 'name' => 'Euro'],
            ['code' => 'RON', 'name' => 'RON'],
            ['code' => 'USD', 'name' => 'USD'],
            ['code' => 'GBP', 'name' => 'GBP'],
        ];

        foreach ($currencies as $currency) {
            DB::table('currencies')->updateOrInsert(
                ['code' => $currency['code']],
                ['name' => $currency['name'], 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }
}

