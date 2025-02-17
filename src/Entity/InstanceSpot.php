<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class InstanceSpot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: InstanceDetail::class, inversedBy: 'spotPrices')]
    #[ORM\JoinColumn(nullable: false)]
    private InstanceDetail $instanceDetail;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 5)]
    private float $spotPrice;

    #[ORM\Column(type: 'string', length: 50)]
    private string $availabilityZone;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $timestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpotPrice(): ?string
    {
        return $this->spotPrice;
    }

    public function setSpotPrice(string $spotPrice): static
    {
        $this->spotPrice = $spotPrice;

        return $this;
    }

    public function getAvailabilityZone(): ?string
    {
        return $this->availabilityZone;
    }

    public function setAvailabilityZone(string $availabilityZone): static
    {
        $this->availabilityZone = $availabilityZone;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getInstanceDetail(): ?InstanceDetail
    {
        return $this->instanceDetail;
    }

    public function setInstanceDetail(?InstanceDetail $instanceDetail): static
    {
        $this->instanceDetail = $instanceDetail;

        return $this;
    }
}
