<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class InstanceDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $instanceType;

    #[ORM\ManyToOne(targetEntity: Provider::class, inversedBy: 'instances')]
    #[ORM\JoinColumn(nullable: false)]
    private Provider $provider;

    #[ORM\Column(type: 'string', length: 50)]
    private string $gpuModel;

    #[ORM\Column(type: 'integer')]
    private int $vram;

    #[ORM\Column(type: 'integer')]
    private int $vcpu;

    #[ORM\Column(type: 'integer')]
    private int $ram;

    #[ORM\Column(type: 'string', length: 20)]
    private string $networkPerformance;

    #[ORM\Column(type: 'text')]
    private string $osSupported;

    #[ORM\OneToMany(mappedBy: 'instanceDetail', targetEntity: InstanceSpot::class, cascade: ['remove'])]
    private Collection $spotPrices;

    public function __construct()
    {
        $this->spotPrices = new ArrayCollection();
    }
}
