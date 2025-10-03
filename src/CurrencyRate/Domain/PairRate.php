<?php

declare(strict_types=1);

namespace App\CurrencyRate\Domain;

use App\CurrencyRate\Domain\Enum\CurrencyPair;

final readonly class PairRate
{
    public function __construct(
        private CurrencyPair $pair,
        private Rate $rate,
    ) {
    }

    public function getPair(): CurrencyPair
    {
        return $this->pair;
    }

    public function getRate(): Rate
    {
        return $this->rate;
    }
}
