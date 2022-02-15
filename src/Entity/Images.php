<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $nameimg;

    #[ORM\ManyToOne(targetEntity: Realisations::class, inversedBy: 'images')]
    private $relation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameimg(): ?string
    {
        return $this->nameimg;
    }

    public function setNameimg(?string $nameimg): self
    {
        $this->nameimg = $nameimg;

        return $this;
    }

    public function getRelation(): ?Realisations
    {
        return $this->relation;
    }

    public function setRelation(?Realisations $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}
