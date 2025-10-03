<?php

declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\QueryBuilder;

use App\CurrencyRate\Application\SearchRequest\CurrencyRateSearchRequest;
use App\CurrencyRate\Infrastructure\Entity\CurrencyRate;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

final readonly class CurrencyRateQueryBuilder
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function build(CurrencyRateSearchRequest $searchRequest): Query
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('cr')
            ->from(CurrencyRate::class, 'cr')
            ->orderBy('cr.timestamp', 'ASC');

        if ($searchRequest->pair) {
            $qb->andWhere('cr.currencyPair = :pair')
                ->setParameter('pair', $searchRequest->pair->value);
        }

        if ($searchRequest->period === 'last-24h') {
            $from = new DateTimeImmutable('-24 hours');
            $qb->andWhere('cr.timestamp >= :from')
                ->setParameter('from', $from);
        }

        if ($searchRequest->period === 'day' && $searchRequest->date) {
            $from = DateTimeImmutable::createFromInterface($searchRequest->date)->setTime(0, 0, 0);
            $to = DateTimeImmutable::createFromInterface($searchRequest->date)->setTime(23, 59, 59);

            $qb->andWhere('cr.timestamp >= :from')
                ->andWhere('cr.timestamp <= :to')
                ->setParameter('from', $from)
                ->setParameter('to', $to);
        }

        return $qb->getQuery();
    }
}
