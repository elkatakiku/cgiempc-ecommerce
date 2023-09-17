<?php

namespace App\Enums;

use PHPUnit\Framework\IncompleteTest;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case ONGOING = 'ongoing';
    case PAID = 'paid';

    public function getText(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::ONGOING => 'Ongoing',
            self::PAID => 'Paid',
        };
    }
}
