<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum PaymentType: string
{
    case FULL = 'full';
    case INSTALLMENT = 'installment';

    public function isFull(): bool
    {
        return $this == self::FULL;
    }

    public function isInstallment(): bool
    {
        return $this == self::INSTALLMENT;
    }

    public function getText(): string
    {
        return match ($this) {
            self::FULL => "Full Payment",
            self::INSTALLMENT => "Installment Payment",
        };
    }

    public static function getTypes(): Collection
    {
        return collect([
            self::FULL->value => self::FULL->getText(),
            self::INSTALLMENT->value => self::INSTALLMENT->getText(),
        ]);
    }
}
