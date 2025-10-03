<?php


declare(strict_types=1);

namespace App\CurrencyRate\Presentation\RequestHandler\Validator;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final readonly class CurrencyRateRequestValidator
{
    private const VALID_PERIODS = ['last-24h', 'day'];

    public function validate(Request $request): void
    {
        $period = $request->attributes->get('period');
        $pair = $request->query->get('pair');
        $date = $request->query->get('date');


        Assert::oneOf($period, self::VALID_PERIODS, 'Invalid period. Must be one of: %2$s');
        Assert::oneOf($pair, CurrencyPair::getAllValues(), 'Invalid currency pair. Must be one of: %2$s');

        if ($period === 'day') {
            Assert::notNull($date, 'Query parameter "date" is required for period "day"');
            Assert::regex($date, '/^\d{4}-\d{2}-\d{2}$/', 'Invalid date format. Expected YYYY-MM-DD');

            $parsedDate = DateTimeImmutable::createFromFormat('Y-m-d', $date);
            Assert::notFalse($parsedDate, 'Invalid date value');
            Assert::eq($parsedDate->format('Y-m-d'), $date, 'Invalid date value');
        }
    }
}
