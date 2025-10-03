<?php

declare(strict_types=1);

namespace App\CurrencyRate\Presentation\RequestHandler;

use App\CurrencyRate\Application\SearchRequest\CurrencyRateSearchRequest;
use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\CurrencyRate\Presentation\RequestHandler\Validator\CurrencyRateRequestValidator;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

final readonly class CurrencyRateRequestHandler
{
    public function __construct(
        private CurrencyRateRequestValidator $validator,
    ) {}

    public function handle(Request $request): CurrencyRateSearchRequest
    {
        $this->validator->validate($request);

        $period = $request->attributes->get('period');
        $dateString = $request->query->get('date');
        $pairString = $request->query->get('pair');

        $date = $dateString ? new DateTimeImmutable($dateString) : null;
        $pair = CurrencyPair::from($pairString);

        return match (true) {
            $period && $date => CurrencyRateSearchRequest::byPeriodAndDate($period, $date, $pair),
            $period !== null => CurrencyRateSearchRequest::byPeriod($period, $pair),
            default => throw new InvalidArgumentException('Invalid input data'),
        };
    }
}
