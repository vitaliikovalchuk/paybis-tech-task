<?php

declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\Repository;

use App\CurrencyRate\Application\Contract\CurrencyRateRepositoryInterface;
use App\CurrencyRate\Application\SearchRequest\CurrencyRateSearchRequest;
use App\CurrencyRate\Domain\CurrencyRate;
use App\CurrencyRate\Domain\CurrencyRates;
use App\CurrencyRate\Infrastructure\DomainMapper\CurrencyRateDomainMapper;
use App\CurrencyRate\Infrastructure\QueryBuilder\CurrencyRateQueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CurrencyRateRepository implements CurrencyRateRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CurrencyRateQueryBuilder $queryBuilder,
        private CurrencyRateDomainMapper $domainMapper,
    ) {
    }

    public function find(CurrencyRateSearchRequest $searchRequest): CurrencyRates
    {
        $query = $this->queryBuilder->build($searchRequest);
        $entities = $query->getResult();

        return $this->domainMapper->mapCollection($entities);
    }

    public function save(CurrencyRate $currencyRate): void
    {
        $entity = $this->domainMapper->mapToEntity($currencyRate);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
