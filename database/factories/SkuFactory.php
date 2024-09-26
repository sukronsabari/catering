<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sku>
 */
class SkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->bothify('SKU-###??'),
            'currency_code' => "IDR",
            'price' => fake()->numberBetween(10000, 1000000),
            'stock' => fake()->numberBetween(10, 1000),
            'weight' => fake()->numberBetween(200, 5000),
            'is_active' => fake()->boolean(),
            'is_default' => fake()->boolean(1),
        ];
    }
}
