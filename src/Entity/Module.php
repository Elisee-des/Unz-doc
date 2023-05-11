<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: Examen::class, orphanRemoval: true)]
    private Collection $examens;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annee $annee = null;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: Correction::class)]
    private Collection $corrections;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: PropositionExamen::class)]
    private Collection $propositionExamens;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: PropositionCorrection::class)]
    private Collection $propositionCorrections;

    public function __construct()
    {
        $this->examens = new ArrayCollection();
        $this->corrections = new ArrayCollection();
        $this->propositionExamens = new ArrayCollection();
        $this->propositionCorrections = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Examen>
     */
    public function getExamens(): Collection
    {
        return $this->examens;
    }

    public function addExamen(Examen $examen): self
    {
        if (!$this->examens->contains($examen)) {
            $this->examens->add($examen);
            $examen->setModule($this);
        }

        return $this;
    }

    public function removeExamen(Examen $examen): self
    {
        if ($this->examens->removeElement($examen)) {
            // set the owning side to null (unless already changed)
            if ($examen->getModule() === $this) {
                $examen->setModule(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getAnnee(): ?Annee
    {
        return $this->annee;
    }

    public function setAnnee(?Annee $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection<int, Correction>
     */
    public function getCorrections(): Collection
    {
        return $this->corrections;
    }

    public function addCorrection(Correction $correction): self
    {
        if (!$this->corrections->contains($correction)) {
            $this->corrections->add($correction);
            $correction->setModule($this);
        }

        return $this;
    }

    public function removeCorrection(Correction $correction): self
    {
        if ($this->corrections->removeElement($correction)) {
            // set the owning side to null (unless already changed)
            if ($correction->getModule() === $this) {
                $correction->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PropositionExamen>
     */
    public function getPropositionExamens(): Collection
    {
        return $this->propositionExamens;
    }

    public function addPropositionExamen(PropositionExamen $propositionExamen): self
    {
        if (!$this->propositionExamens->contains($propositionExamen)) {
            $this->propositionExamens->add($propositionExamen);
            $propositionExamen->setModule($this);
        }

        return $this;
    }

    public function removePropositionExamen(PropositionExamen $propositionExamen): self
    {
        if ($this->propositionExamens->removeElement($propositionExamen)) {
            // set the owning side to null (unless already changed)
            if ($propositionExamen->getModule() === $this) {
                $propositionExamen->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PropositionCorrection>
     */
    public function getPropositionCorrections(): Collection
    {
        return $this->propositionCorrections;
    }

    public function addPropositionCorrection(PropositionCorrection $propositionCorrection): self
    {
        if (!$this->propositionCorrections->contains($propositionCorrection)) {
            $this->propositionCorrections->add($propositionCorrection);
            $propositionCorrection->setModule($this);
        }

        return $this;
    }

    public function removePropositionCorrection(PropositionCorrection $propositionCorrection): self
    {
        if ($this->propositionCorrections->removeElement($propositionCorrection)) {
            // set the owning side to null (unless already changed)
            if ($propositionCorrection->getModule() === $this) {
                $propositionCorrection->setModule(null);
            }
        }

        return $this;
    }

}
