<?php

declare(strict_types=1);

namespace App\Scheduler\Application\Service;

use App\Scheduler\Application\Contract\StoreCurrencyRateServiceInterface;
use App\Scheduler\Application\Message\UpdateCurrencyRatesMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
final readonly class UpdateCurrencyRatesMessageHandler
{
    public function __construct(
        private StoreCurrencyRateServiceInterface $storeCurrencyRateService,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(UpdateCurrencyRatesMessage $message): void
    {
        $this->logger->info('Starting currency rates update via scheduler');

        try {
            $this->storeCurrencyRateService->process();
            $this->logger->info('Successfully updated all currency rates');
        } catch (Throwable $e) {
            $this->logger->error('Failed to update currency rates', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
