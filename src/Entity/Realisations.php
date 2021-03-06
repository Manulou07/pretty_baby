<?php

namespace App\Entity;

use App\Repository\RealisationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealisationsRepository::class)]
class Realisations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 30)]
    private $img;

    #[ORM\Column(type: 'string', length: 100)]
    private $titre;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\OneToMany(mappedBy: 'relation', targetEntity: Images::class)]
    private $images;

    #[ORM\OneToOne(mappedBy: 'fk_id_realisations', targetEntity: Commentaires::class, cascade: ['persist', 'remove'])]
    private $commentaires;

    #[ORM\OneToOne(targetEntity: Reservations::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $fkIdReservations;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setRelation($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getRelation() === $this) {
                $image->setRelation(null);
            }
        }

        return $this;
    }

    public function getCommentaires(): ?Commentaires
    {
        return $this->commentaires;
    }

    public function setCommentaires(Commentaires $commentaires): self
    {
        // set the owning side of the relation if necessary
        if ($commentaires->getFkIdRealisations() !== $this) {
            $commentaires->setFkIdRealisations($this);
        }

        $this->commentaires = $commentaires;

        return $this;
    }

    public function getFkIdReservations(): ?Reservations
    {
        return $this->fkIdReservations;
    }

    public function setFkIdReservations(Reservations $fkIdReservations): self
    {
        $this->fkIdReservations = $fkIdReservations;

        return $this;
    }
}
