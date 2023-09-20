<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum TypeOfPayment: string
{
    case FULL = 'full';
    case INSTALLMENT = 'installment';

    public function isFullPayment(): bool
    {
        return $this == self::FULL;
    }

    public function isInstallmentPayment(): bool
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

    public function getTypes(): Collection
    {
        return collect([
            self::FULL->value => self::FULL->getText(),
            self::INSTALLMENT->value => self::INSTALLMENT->getText(),
        ]);
    }
}
