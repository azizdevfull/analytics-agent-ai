<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'product_id' => Product::factory(),
            'status' => fake()->randomElement([
                'pending',
                'processing',
                'shipped',
                'delivered',
            ]),
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}
