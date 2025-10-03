<?php

declare(strict_types=1);

namespace App\CurrencyRate\Presentation\Bridge\Scheduler\Service;

use App\CurrencyRate\Application\Service\StoreCurrencyRateService;
use App\Scheduler\Application\Contract\StoreCurrencyRateServiceInterface;

final readonly class StoreCurrencyRateServiceBridge implements StoreCurrencyRateServiceInterface
{
    public function __construct(private StoreCurrencyRateService $service) {}

    public function process(): void
    {
        $this->service->storeAllRates();
    }
}
