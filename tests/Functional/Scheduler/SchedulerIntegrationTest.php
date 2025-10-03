<?php

declare(strict_types=1);

namespace App\Tests\Functional\Scheduler;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\Scheduler\Application\Message\UpdateCurrencyRatesMessage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

final class SchedulerIntegrationTest extends KernelTestCase
{
    private MessageBusInterface $messageBus;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->messageBus = self::getContainer()->get(MessageBusInterface::class);
    }

    public function testSchedulerStoresRatesToDatabase(): void
    {
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->getConnection()->executeStatement('DELETE FROM currency_rates');

        $this->messageBus->dispatch(new UpdateCurrencyRatesMessage());

        sleep(1);

        $connection = $entityManager->getConnection();
        $result = $connection->executeQuery('SELECT COUNT(*) as count FROM currency_rates')->fetchAssociative();

        $this->assertGreaterThanOrEqual(3, $result['count'], 'Expected at least 3 currency rates to be stored');

        foreach (CurrencyPair::cases() as $pair) {
            $pairResult = $connection->executeQuery(
                'SELECT * FROM currency_rates WHERE currency_pair = ? ORDER BY timestamp DESC LIMIT 1',
                [$pair->value]
            )->fetchAssociative();

            $this->assertNotFalse($pairResult, "Expected rate for {$pair->value} to be stored");
            $this->assertNotEmpty($pairResult['rate'], "Expected rate value for {$pair->value}");
            $this->assertGreaterThan(0, (float)$pairResult['rate'], "Expected positive rate for {$pair->value}");
        }
    }

}
