<?php

namespace App\Entity;

use App\Repository\ReunionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReunionRepository::class)]
class Reunion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    /**
     * @var Collection<int, InscriptionReunion>
     */
    #[ORM\OneToMany(targetEntity: InscriptionReunion::class, mappedBy: 'reunion', orphanRemoval: true)]
    private Collection $inscriptionReunions;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max:1000,
        maxMessage:"Vous ne pouvez pas faire un message de plus de {{ limit }} caractères",
    )]
    private ?string $content = null;

    

    public function __construct()
    {
        $this->inscriptionReunions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection<int, InscriptionReunion>
     */
    public function getInscriptionReunions(): Collection
    {
        return $this->inscriptionReunions;
    }

    public function addInscriptionReunion(InscriptionReunion $inscriptionReunion): static
    {
        if (!$this->inscriptionReunions->contains($inscriptionReunion)) {
            $this->inscriptionReunions->add($inscriptionReunion);
            $inscriptionReunion->setReunion($this);
        }

        return $this;
    }

    public function removeInscriptionReunion(InscriptionReunion $inscriptionReunion): static
    {
        if ($this->inscriptionReunions->removeElement($inscriptionReunion)) {
            // set the owning side to null (unless already changed)
            if ($inscriptionReunion->getReunion() === $this) {
                $inscriptionReunion->setReunion(null);
            }
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
