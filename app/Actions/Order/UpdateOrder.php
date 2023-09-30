<?php

namespace App\Actions\Order;

use App\Models\Order;

class UpdateOrder
{
    public function handle(Order $order, array $data)
    {
        $order->update([
            'status' => $data['status'],
        ]);
    }
}
