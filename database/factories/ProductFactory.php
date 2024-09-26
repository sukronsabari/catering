<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'name' => fake()->randomElement(['Ikan', 'Bakar', 'Rebus', 'Goreng', 'Santan', 'Padang', 'Nasi']),
            'description' => fake()->paragraph(),
            'is_active' => fake()->boolean(),
            'currency_code' => 'IDR',
            'price' => fake()->numberBetween(10000, 1000000),
            'stock' => fake()->numberBetween(10, 1000),
            'weight' => fake()->numberBetween(200, 5000),
            'sku' => fake()->bothify('SKU-###??'),
            'has_variation' => false,
        ];
    }

    public function withImages()
    {
        return $this->afterCreating(function (Product $product) {
            $product->images()->createMany([
                ['image' => 'images/default/image.png', 'is_main' => true],
                ['image' => 'images/default/image.png', 'is_main' => false],
            ]);
        });
    }

    public function withVariation(): Factory
    {
        return $this->state(function () {
            return ['has_variation' => true];
        })->afterCreating(function (Product $product) {
            $attribute1 = ProductAttribute::factory()->make([
                'name' => 'size'
            ]);
            $attribute2 = ProductAttribute::factory()->make([
                'name' => 'flavour',
            ]);

            $attribute1->product_id = $product->id;
            $attribute2->product_id = $product->id;

            $attribute1->save();
            $attribute2->save();


            $sku = Sku::factory()->make([]);
            $sku->product_id = $product->id;
            $sku->save();

            $sku->productAttributes()->attach([
                $attribute1->id => ['value' => fake()->randomElement(['Large', 'Small'])], // Size values
                $attribute2->id => ['value' => fake()->randomElement(['Original', 'Mix', 'Full Mix'])], // Flavour values
            ]);
        });
    }
}
