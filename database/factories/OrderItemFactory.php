<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'shop_id' => fn (array $attributes) => Product::query()->find($attributes['product_id'])?->shop_id,
            'product_name' => fn (array $attributes) => Product::query()->find($attributes['product_id'])?->name ?? ucfirst(fake()->words(3, true)),
            'unit_price' => fn (array $attributes) => Product::query()->find($attributes['product_id'])?->price ?? fake()->numberBetween(500, 250000),
            'quantity' => fake()->numberBetween(1, 5),
            'line_total' => fn (array $attributes) => $attributes['unit_price'] * $attributes['quantity'],
        ];
    }
}
