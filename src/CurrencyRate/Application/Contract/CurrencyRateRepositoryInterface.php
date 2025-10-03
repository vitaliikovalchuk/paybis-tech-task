<?php


declare(strict_types=1);

namespace App\CurrencyRate\Application\Contract;

use App\CurrencyRate\Application\SearchRequest\CurrencyRateSearchRequest;
use App\CurrencyRate\Domain\CurrencyRate;
use App\CurrencyRate\Domain\CurrencyRates;

interface CurrencyRateRepositoryInterface
{
    public function find(CurrencyRateSearchRequest $searchRequest): CurrencyRates;

    public function save(CurrencyRate $currencyRate): void;
}
