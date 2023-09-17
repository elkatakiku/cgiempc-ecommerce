<?php

namespace App\Enums;

enum PaymentType: string
{
    case FULL = 'full';
    case INSTALLMENT = 'installment';

    public function getText(): string
    {
        return match ($this) {
            self::FULL => 'Full Payment',
            self::INSTALLMENT => 'Installment Payment',
        };
    }
}
