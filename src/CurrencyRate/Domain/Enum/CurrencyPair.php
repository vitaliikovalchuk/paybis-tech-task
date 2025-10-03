<?php

declare(strict_types=1);

namespace App\CurrencyRate\Domain\Enum;

enum CurrencyPair: string
{
    case EUR_BTC = 'EUR/BTC';
    case EUR_ETH = 'EUR/ETH';
    case EUR_LTC = 'EUR/LTC';

    public static function getAllValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
