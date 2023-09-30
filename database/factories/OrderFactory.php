<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\Product;
use App\Models\User;
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
        $product = Product::query()->inRandomOrder()->first();

        return [
            'user_id' => User::query()->inRandomOrder()->first(),
            'product_id' => $product->id,
            'price' => $product->price,
            'quantity' => fake()->numberBetween(1, 50),
            'payment_type' => PaymentType::getTypes()->keys()->random(),
            'status' => OrderStatus::getStatuses()->keys()->random(),
        ];
    }
}
