<?php

declare(strict_types=1);

namespace App\CurrencyRate\Domain;

use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class CurrencyRate
{
    public function __construct(
        private UuidInterface $id,
        private PairRate $pairRate,
        private DateTimeInterface $timestamp,
        private DateTimeInterface $createdAt,
    ) {}

    public static function new(
        PairRate $pairRate,
        DateTimeInterface $timestamp
    ): self {
        return new self(
            Uuid::uuid4(),
            $pairRate,
            $timestamp,
            $timestamp
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPairRate(): PairRate
    {
        return $this->pairRate;
    }

    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
