<?php

declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\Schedule;

use App\CurrencyRate\Application\Message\UpdateCurrencyRatesMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('currency_rates')]
final readonly class CurrencyRateScheduleProvider implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(
                RecurringMessage::every('5 minutes', new UpdateCurrencyRatesMessage())
            );
    }
}
