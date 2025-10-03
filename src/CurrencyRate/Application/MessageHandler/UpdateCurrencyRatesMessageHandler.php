<?php

declare(strict_types=1);

namespace App\CurrencyRate\Application\MessageHandler;

use App\CurrencyRate\Application\Contract\CurrencyRateRepositoryInterface;
use App\CurrencyRate\Application\Message\UpdateCurrencyRatesMessage;
use App\CurrencyRate\Domain\CurrencyRate;
use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\CurrencyRate\Domain\Rate;
use App\CurrencyRate\Infrastructure\ExternalApi\CryptoRateApiClient;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateCurrencyRatesMessageHandler
{
    public function __construct(
        private CryptoRateApiClient $apiClient,
        private CurrencyRateRepositoryInterface $repository,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(UpdateCurrencyRatesMessage $message): void
    {
        $this->logger->info('Starting currency rates update');

        $pairs = [
            CurrencyPair::EUR_BTC,
            CurrencyPair::EUR_ETH,
            CurrencyPair::EUR_LTC,
        ];

        $now = new DateTimeImmutable();

        foreach ($pairs as $pair) {
            try {
                $rateValue = $this->apiClient->fetchRate($pair);

                $currencyRate = new CurrencyRate(
                    id: Uuid::uuid4(),
                    currencyPair: $pair,
                    rate: new Rate($rateValue),
                    timestamp: $now,
                    createdAt: $now,
                );

                $this->repository->save($currencyRate);

                $this->logger->info('Saved rate', [
                    'pair' => $pair->value,
                    'rate' => $rateValue,
                ]);
            } catch (\Throwable $e) {
                $this->logger->error('Failed to update rate', [
                    'pair' => $pair->value,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->logger->info('Finished currency rates update');
    }
}
