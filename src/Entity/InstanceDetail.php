<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstanceType(): ?string
    {
        return $this->instanceType;
    }

    public function setInstanceType(string $instanceType): static
    {
        $this->instanceType = $instanceType;

        return $this;
    }

    public function getGpuModel(): ?string
    {
        return $this->gpuModel;
    }

    public function setGpuModel(string $gpuModel): static
    {
        $this->gpuModel = $gpuModel;

        return $this;
    }

    public function getVram(): ?int
    {
        return $this->vram;
    }

    public function setVram(int $vram): static
    {
        $this->vram = $vram;

        return $this;
    }

    public function getVcpu(): ?int
    {
        return $this->vcpu;
    }

    public function setVcpu(int $vcpu): static
    {
        $this->vcpu = $vcpu;

        return $this;
    }

    public function getRam(): ?int
    {
        return $this->ram;
    }

    public function setRam(int $ram): static
    {
        $this->ram = $ram;

        return $this;
    }

    public function getNetworkPerformance(): ?string
    {
        return $this->networkPerformance;
    }

    public function setNetworkPerformance(string $networkPerformance): static
    {
        $this->networkPerformance = $networkPerformance;

        return $this;
    }

    public function getOsSupported(): ?string
    {
        return $this->osSupported;
    }

    public function setOsSupported(string $osSupported): static
    {
        $this->osSupported = $osSupported;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return Collection<int, InstanceSpot>
     */
    public function getSpotPrices(): Collection
    {
        return $this->spotPrices;
    }

    public function addSpotPrice(InstanceSpot $spotPrice): static
    {
        if (!$this->spotPrices->contains($spotPrice)) {
            $this->spotPrices->add($spotPrice);
            $spotPrice->setInstanceDetail($this);
        }

        return $this;
    }

    public function removeSpotPrice(InstanceSpot $spotPrice): static
    {
        if ($this->spotPrices->removeElement($spotPrice)) {
            // set the owning side to null (unless already changed)
            if ($spotPrice->getInstanceDetail() === $this) {
                $spotPrice->setInstanceDetail(null);
            }
        }

        return $this;
    }
}
