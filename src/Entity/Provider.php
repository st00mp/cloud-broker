<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Provider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: InstanceDetail::class)]
    private Collection $instances;

    public function __construct()
    {
        $this->instances = new ArrayCollection();
    }

    /**
     * Getter name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter name (pour pouvoir faire setName('AWS')).
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, InstanceDetail>
     */
    public function getInstances(): Collection
    {
        return $this->instances;
    }

    public function addInstance(InstanceDetail $instance): static
    {
        if (!$this->instances->contains($instance)) {
            $this->instances->add($instance);
            $instance->setProvider($this);
        }

        return $this;
    }

    public function removeInstance(InstanceDetail $instance): static
    {
        if ($this->instances->removeElement($instance)) {
            // set the owning side to null (unless already changed)
            if ($instance->getProvider() === $this) {
                $instance->setProvider(null);
            }
        }

        return $this;
    }
}
