<?php

declare(strict_types=1);

namespace App\CurrencyRate\Application\Contract;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\CurrencyRate\Domain\PairRates;

interface RateProviderInterface
{
    /**
     * @param CurrencyPair[] $pairs
     */
    public function fetchRates(array $pairs): PairRates;
}
