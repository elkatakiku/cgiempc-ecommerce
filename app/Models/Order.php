<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Helpers\ModelPropertyCaster;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'product_id',
        'price',
        'quantity',
        'total',
        'balance',
        'payment_type',
        'status',
    ];

    protected $casts = [
        'payment_type' => PaymentType::class,
        'status' => OrderStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function price(): Attribute
    {
        return ModelPropertyCaster::toMoney();
    }

    public function total(): Attribute
    {
        return ModelPropertyCaster::toMoney();
    }

    public function balance(): Attribute
    {
        return ModelPropertyCaster::toMoney();
    }
}
