<?php

namespace App\Models;

use App\Helpers\ModelPropertyCaster;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_id',
        'payment',
        'paid_by',
        'received_by',
    ];

    public function payment(): Attribute
    {
        return ModelPropertyCaster::toMoney();
    }
}
