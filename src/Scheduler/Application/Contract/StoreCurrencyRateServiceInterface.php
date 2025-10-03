<?php

declare(strict_types=1);

namespace App\Scheduler\Application\Contract;

interface StoreCurrencyRateServiceInterface
{
    public function process(): void;
}
