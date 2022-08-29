<?php

namespace App\Infrastructure\Entity;

use App\Domain\Model\ElectricVehicle;
use App\Domain\Model\Location;
use App\Domain\Model\Vehicle;
use App\Infrastructure\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car implements Location, Vehicle
{
    use ElectricVehicle;
    #[ORM\Id]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $seats;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Gang::class)]
    private Collection $gangs;

    public function __construct()
    {
        $this->gangs = new ArrayCollection();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSeats(): int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): self
    {
        $this->seats = $seats;

        return $this;
    }

    /**
     * @return Collection<int, Gang>
     */
    public function getGangs(): Collection
    {
        return $this->gangs;
    }

    public function addGang(Gang $gang): self
    {
        if (!$this->gangs->contains($gang)) {
            $this->gangs->add($gang);
            $gang->setCar($this);
        }

        return $this;
    }

    public function removeGang(Gang $gang): self
    {
        if ($this->gangs->removeElement($gang)) {
            // set the owning side to null (unless already changed)
            if ($gang->getCar() === $this) {
                $gang->setCar(null);
            }
        }

        return $this;
    }
}
