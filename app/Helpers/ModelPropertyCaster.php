<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Casts\Attribute;

class ModelPropertyCaster
{
    public static function toMoney(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value * .01,
            set: fn($value) => $value / .01,
        );
    }
}
