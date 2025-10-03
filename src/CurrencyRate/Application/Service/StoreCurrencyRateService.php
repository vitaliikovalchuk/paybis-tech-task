<?php

declare(strict_types=1);

namespace App\CurrencyRate\Application\Service;

use App\CurrencyRate\Application\Contract\CurrencyRateRepositoryInterface;
use App\CurrencyRate\Application\Contract\RateProviderInterface;
use App\CurrencyRate\Domain\CurrencyRate;
use App\CurrencyRate\Domain\Enum\CurrencyPair;
use Psr\Clock\ClockInterface;

final readonly class StoreCurrencyRateService
{
    public function __construct(
        private RateProviderInterface $rateProvider,
        private CurrencyRateRepositoryInterface $repository,
        private ClockInterface $clock
    ) {}

    public function storeAllRates(): void
    {
        $pairs = CurrencyPair::cases();
        $pairRates = $this->rateProvider->fetchRates($pairs);
        $now = $this->clock->now();

        foreach ($pairRates->getItems() as $pairRate) {
            $currencyRate = CurrencyRate::new(
                pairRate: $pairRate,
                timestamp: $now,
            );

            $this->repository->save($currencyRate);
        }
    }
}
