<?php

declare(strict_types=1);

namespace App\CurrencyRate\Infrastructure\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'currency_rates')]
#[ORM\Index(name: 'idx_pair_timestamp', columns: ['currency_pair', 'timestamp'])]
#[ORM\Index(name: 'idx_timestamp', columns: ['timestamp'])]
class CurrencyRate
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface $id;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private string $currencyPair;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 8)]
    private string $rate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $timestamp;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    public function setId(UuidInterface $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCurrencyPair(): string
    {
        return $this->currencyPair;
    }

    public function setCurrencyPair(string $currencyPair): self
    {
        $this->currencyPair = $currencyPair;
        return $this;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
