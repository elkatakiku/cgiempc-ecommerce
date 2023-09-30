<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member' => $this->user->name,
            'product' => $this->product->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'balance' => $this->balance,
            'payment_type' => $this->payment_type->getText(),
            'status' => $this->status->getText(),
            'ordered_at' => $this->created_at,
            'last_update' => $this->updated_at,
        ];
    }
}
