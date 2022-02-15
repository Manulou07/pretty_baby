<?php

namespace App\Entity;

use App\Repository\ForfaitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForfaitRepository::class)]
class Forfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $type_forfait;

    #[ORM\Column(type: 'float')]
    private $prix_forfait;

    #[ORM\OneToMany(mappedBy: 'fk_id_forfait', targetEntity: Reservations::class)]
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeForfait(): ?string
    {
        return $this->type_forfait;
    }

    public function setTypeForfait(string $type_forfait): self
    {
        $this->type_forfait = $type_forfait;

        return $this;
    }

    public function getPrixForfait(): ?float
    {
        return $this->prix_forfait;
    }

    public function setPrixForfait(float $prix_forfait): self
    {
        $this->prix_forfait = $prix_forfait;

        return $this;
    }

    /**
     * @return Collection|Reservations[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservations $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setFkIdForfait($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getFkIdForfait() === $this) {
                $reservation->setFkIdForfait(null);
            }
        }

        return $this;
    }
}
