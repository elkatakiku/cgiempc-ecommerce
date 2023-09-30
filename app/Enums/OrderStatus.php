<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case ONGOING = 'ongoing';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function getText(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::ONGOING => 'Ongoing',
            self::PAID => 'Paid',
            self::CANCELLED => 'Cancelled',
        };
    }

    public static function getStatuses(): Collection
    {
        return collect([
            self::PENDING->value => self::PENDING->getText(),
            self::APPROVED->value => self::APPROVED->getText(),
            self::ONGOING->value => self::ONGOING->getText(),
            self::PAID->value => self::PAID->getText(),
            self::CANCELLED->value => self::CANCELLED->getText(),
        ]);
    }
}
