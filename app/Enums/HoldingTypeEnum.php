<?php

namespace App\Enums;

enum HoldingTypeEnum: string
{
    case STOCK = 'stock';
    case CRYPTO = 'cypto';
    case FUND = 'fund';
    case BOND = 'bond';
    case CASH = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::STOCK => 'Stock',
            self::CRYPTO => 'Crypto',
            self::FUND => 'Fund',
            self::BOND => 'Bond',
            self::CASH => 'Cash'
        };
    }

    public function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
