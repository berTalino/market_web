<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'shop_id' => Shop::factory(),
            'name' => ucfirst(fake()->unique()->words(3, true)),
            'slug' => fake()->unique()->slug(3),
            'description' => fake()->optional()->paragraph(),
            'price' => fake()->numberBetween(500, 250000),
            'stock' => fake()->numberBetween(0, 50),
            'is_active' => fake()->boolean(90),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}
