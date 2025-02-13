<?php

namespace App\Entity;

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
}
