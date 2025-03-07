<?php

namespace App\Entity;

use App\Repository\ParcelleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// Liste des contraintes https://symfony.com/doc/current/validation.html#constraints
// = vérifications/contraintes avant d'insérer en base de données (je veux que les données soient inférieur à , supérieure à etc...)
// Les asserts (contraintes) sont lu au moment du $form->handleRequest($request)
#[ORM\Entity(repositoryClass: ParcelleRepository::class)]
class Parcelle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le numéro de la parcelle doit être supérieur à 0")]
    /**
     * Numéro de la pacelle (1 pour première parcelle, 2 pour la deuxième etc...)
     */
    private ?int $number = null;

    #[ORM\Column]
    #[Assert\NotBlank()] // !empty()
    #[Assert\LessThanOrEqual(value: 100, message: 'La superficie ne peut pas être supérieur à 100m²')]
    #[Assert\Positive(message: 'La superficie doit être supérieur à 0m²')]
    private ?float $size = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Plante>
     */
    #[ORM\OneToMany(targetEntity: Plante::class, mappedBy: 'parcelle', orphanRemoval: true)]
    private Collection $plantes;

    #[ORM\OneToOne(mappedBy: 'parcelle', cascade: ['persist', 'remove'])]
    private ?User $owner = null;

    public function __construct()
    {
        $this->plantes = new ArrayCollection();
    }
    public function __toString()
    {
        return "numéro #" . $this->getNumber();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Plante>
     */
    public function getPlantes(): Collection
    {
        return $this->plantes;
    }

    public function addPlante(Plante $plante): static
    {
        if (!$this->plantes->contains($plante)) {
            $this->plantes->add($plante);
            $plante->setParcelle($this);
        }

        return $this;
    }

    public function removePlante(Plante $plante): static
    {
        if ($this->plantes->removeElement($plante)) {
            // set the owning side to null (unless already changed)
            if ($plante->getParcelle() === $this) {
                $plante->setParcelle(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        // unset the owning side of the relation if necessary
        if ($owner === null && $this->owner !== null) {
            $this->owner->setParcelle(null);
        }

        // set the owning side of the relation if necessary
        if ($owner !== null && $owner->getParcelle() !== $this) {
            $owner->setParcelle($this);
        }

        $this->owner = $owner;

        return $this;
    }
}
