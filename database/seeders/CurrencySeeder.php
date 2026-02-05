<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'symbol' => 'USD',
            'exchange_rate' => 1.00000000,
        ]);

        Currency::create([
            'name' => 'Euro',
            'symbol' => 'EUR',
            'exchange_rate' => 0.85000000,
        ]);

        Currency::factory()->count(5)->create();
    }
}
