<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PriceStatus: string implements HasLabel
{
    case Active = 'active';
    case Expired = 'expired';
    case Future = 'future';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Future => 'Future',
        };
    }
}
