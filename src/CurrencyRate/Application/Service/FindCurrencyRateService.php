<?php

namespace App\CurrencyRate\Application\Service;

use App\CurrencyRate\Application\Contract\CurrencyRateRepositoryInterface;
use App\CurrencyRate\Application\SearchRequest\CurrencyRateSearchRequest;
use App\CurrencyRate\Domain\CurrencyRates;

readonly class FindCurrencyRateService
{
    public function __construct(
        private CurrencyRateRepositoryInterface $repository
    ) {
    }

    public function find(CurrencyRateSearchRequest $searchRequest): CurrencyRates
    {
        return $this->repository->find($searchRequest);
    }
}
