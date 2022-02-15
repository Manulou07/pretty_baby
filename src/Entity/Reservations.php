<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $date_resa;

    #[ORM\Column(type: 'date')]
    private $date_prestation;

    #[ORM\Column(type: 'text')]
    private $msg_resa;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    private $fk_id_user;

    #[ORM\ManyToOne(targetEntity: Forfait::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private $fk_id_forfait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateResa(): ?\DateTimeInterface
    {
        return $this->date_resa;
    }

    public function setDateResa(\DateTimeInterface $date_resa): self
    {
        $this->date_resa = $date_resa;

        return $this;
    }

    public function getDatePrestation(): ?\DateTimeInterface
    {
        return $this->date_prestation;
    }

    public function setDatePrestation(\DateTimeInterface $date_prestation): self
    {
        $this->date_prestation = $date_prestation;

        return $this;
    }

    public function getMsgResa(): ?string
    {
        return $this->msg_resa;
    }

    public function setMsgResa(string $msg_resa): self
    {
        $this->msg_resa = $msg_resa;

        return $this;
    }

    public function getFkIdUser(): ?User
    {
        return $this->fk_id_user;
    }

    public function setFkIdUser(?User $fk_id_user): self
    {
        $this->fk_id_user = $fk_id_user;

        return $this;
    }

    public function getFkIdForfait(): ?Forfait
    {
        return $this->fk_id_forfait;
    }

    public function setFkIdForfait(?Forfait $fk_id_forfait): self
    {
        $this->fk_id_forfait = $fk_id_forfait;

        return $this;
    }
}