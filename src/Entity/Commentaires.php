<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $comment;

    #[ORM\Column(type: 'boolean')]
    private $publish;

    #[ORM\OneToOne(inversedBy: 'commentaires', targetEntity: Realisations::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private $fk_id_realisations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): self
    {
        $this->publish = $publish;

        return $this;
    }

    public function getFkIdRealisations(): ?realisations
    {
        return $this->fk_id_realisations;
    }

    public function setFkIdRealisations(realisations $fk_id_realisations): self
    {
        $this->fk_id_realisations = $fk_id_realisations;

        return $this;
    }
}
