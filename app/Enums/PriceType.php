<?php

namespace App\Enums;

enum PriceType: string
{
    case Standard = 'standard';
    case Promotion = 'promotion';

    /**
     * @return array<array-key, string>
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Standard => 'Standard',
            self::Promotion => 'Promotion',
        };
    }
}
