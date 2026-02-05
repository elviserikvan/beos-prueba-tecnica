<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 10, 5000),
            'currency_id' => Currency::query()->inRandomOrder()->value('id') ?? Currency::factory(),
            'tax_cost' => $this->faker->randomFloat(2, 0, 1000),
            'manufacturing_cost' => $this->faker->randomFloat(2, 5, 3000),
        ];
    }
}
