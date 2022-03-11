<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisponibiliteRepository::class)]
class Disponibilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $date_dispo;

    #[ORM\Column(type: 'boolean')]
    private $isBook;

    #[ORM\OneToOne(mappedBy: 'date_prestation', targetEntity: Reservations::class, cascade: ['persist', 'remove'])]
    private $reservations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDispo(): ?\DateTimeInterface
    {
        return $this->date_dispo;
    }

    public function setDateDispo(\DateTimeInterface $date_dispo): self
    {
        $this->date_dispo = $date_dispo;

        return $this;
    }

    public function getIsBook(): ?bool
    {
        return $this->isBook;
    }

    public function setIsBook(bool $isBook): self
    {
        $this->isBook = $isBook;

        return $this;
    }

    public function getReservations(): ?Reservations
    {
        return $this->reservations;
    }

    public function setReservations(Reservations $reservations): self
    {
        // set the owning side of the relation if necessary
        if ($reservations->getDatePrestation() !== $this) {
            $reservations->setDatePrestation($this);
        }

        $this->reservations = $reservations;

        return $this;
    }
}
