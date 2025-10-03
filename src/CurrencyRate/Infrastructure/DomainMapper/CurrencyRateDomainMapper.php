<?php


declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\DomainMapper;

use App\CurrencyRate\Domain\Enum\CurrencyPair;
use App\CurrencyRate\Domain\CurrencyRate as CurrencyRateDomain;
use App\CurrencyRate\Domain\CurrencyRates as DomainCurrencyRates;
use App\CurrencyRate\Domain\PairRate;
use App\CurrencyRate\Domain\Rate;
use App\CurrencyRate\Infrastructure\Entity\CurrencyRate as CurrencyRateEntity;

final readonly class CurrencyRateDomainMapper
{
    public function mapToDomain(CurrencyRateEntity $entity): CurrencyRateDomain
    {
        return new CurrencyRateDomain(
            id: $entity->getId(),
            pairRate: new PairRate(
                CurrencyPair::from($entity->getCurrencyPair()),
                new Rate($entity->getRate())
            ),
            timestamp: $entity->getTimestamp(),
            createdAt: $entity->getCreatedAt(),
        );
    }

    public function mapCollection(array $entities): DomainCurrencyRates
    {
        $items = array_map(
            fn(CurrencyRateEntity $entity) => $this->mapToDomain($entity),
            $entities
        );

        return new DomainCurrencyRates($items);
    }

    public function mapToEntity(CurrencyRateDomain $domain): CurrencyRateEntity
    {
        $entity = new CurrencyRateEntity();
        $entity->setId($domain->getId());
        $entity->setCurrencyPair($domain->getPairRate()->getPair()->value);
        $entity->setRate($domain->getPairRate()->getRate()->getValue());
        $entity->setTimestamp($domain->getTimestamp());
        $entity->setCreatedAt($domain->getCreatedAt());

        return $entity;
    }
}
