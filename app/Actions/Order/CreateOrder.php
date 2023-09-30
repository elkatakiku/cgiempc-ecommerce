<?php

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;

class CreateOrder
{
    public function handle(array $orderData, string $user_id): Order
    {
        $product = Product::where('slug', $orderData['product'])->first();
        $total = $product->price * $orderData['quantity'];

        return Order::create([
            'user_id' => $user_id,
            'product_id' => $product->id,
            'price' => $product->price,
            'quantity' => $orderData['quantity'],
            'total' => $total,
            'balance' => $total,
            'payment_type' => $orderData['payment_type'],
            'status' => OrderStatus::PENDING,
        ]);
    }
}
