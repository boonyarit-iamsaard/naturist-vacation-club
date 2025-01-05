<?php

namespace App\Enums;

enum PriceStatus: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Future = 'future';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Future => 'Future',
        };
    }
}
