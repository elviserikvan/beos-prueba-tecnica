<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductPriceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $currencies = Currency::all();
        if ($currencies->isEmpty()) {
            Currency::factory()->count(3)->create();
            $currencies = Currency::all();
        }

        // Remove existing prices to avoid duplicates when seeding repeatedly
        ProductPrice::query()->delete();

        Product::query()->lazy()->each(function (Product $product) use ($currencies, $faker) {
            foreach ($currencies as $currency) {
                ProductPrice::factory()->create([
                    'product_id' => $product->id,
                    'currency_id' => $currency->id,
                    'price' => $faker->randomFloat(2, 5, 5000),
                ]);
            }
        });
    }
}
