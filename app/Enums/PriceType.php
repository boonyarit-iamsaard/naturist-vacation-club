<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PriceType: string implements HasLabel
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

    public function getLabel(): string
    {
        return match ($this) {
            self::Standard => 'Standard',
            self::Promotion => 'Promotion',
        };
    }
}
