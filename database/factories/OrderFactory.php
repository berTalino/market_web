<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->numerify('+7999#######'),
            'customer_email' => fake()->optional()->safeEmail(),
            'comment' => fake()->optional()->sentence(),
            'status' => OrderStatus::PENDING,
            'subtotal_amount' => 0,
            'total_amount' => 0,
            'session_id' => fake()->optional()->uuid(),
        ];
    }
}
