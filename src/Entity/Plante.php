<?php

namespace App\Entity;

use App\Repository\PlanteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlanteRepository::class)]
class Plante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 1,
        max: 40,
        minMessage:"Le nom de la plante doit contenir minimum {{ limit }} caractère(s)",
        maxMessage:"Le nom de la plante doit être inférieur à {{ limit }} caractère(s)"
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)] 
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePlantation = null;

    #[ORM\Column]
    private ?int $periodeCroissance = null;

    #[ORM\ManyToOne(inversedBy: 'plantes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parcelle $parcelle = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Permet de modifier le nom de la plante
     */
    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Permet de récupérer le type de plante (légume, fruit, aromatique ...)
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Permet de modifier le type de plante (légume, fruit, aromatique ...)
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDatePlantation(): ?\DateTimeInterface
    {
        return $this->datePlantation;
    }

    public function setDatePlantation(\DateTimeInterface $datePlantation): static
    {
        $this->datePlantation = $datePlantation;

        return $this;
    }

    public function getPeriodeCroissance(): ?int
    {
        return $this->periodeCroissance;
    }

    public function setPeriodeCroissance(int $periodeCroissance): static
    {
        $this->periodeCroissance = $periodeCroissance;

        return $this;
    }

    public function getParcelle(): ?Parcelle
    {
        return $this->parcelle;
    }

    public function setParcelle(?Parcelle $parcelle): static
    {
        $this->parcelle = $parcelle;

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
}
