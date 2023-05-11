<?php

namespace App\Entity;

use App\Repository\PropositionExamenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropositionExamenRepository::class)]
class PropositionExamen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomPrenom = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    private ?string $examen = null;

    #[ORM\Column(length: 255)]
    private ?string $examenNom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'propositionExamens')]
    private ?Module $module = null;

    #[ORM\Column(length: 255)]
    private ?string $nomSession = null;

    #[ORM\Column]
    private ?bool $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPrenom(): ?string
    {
        return $this->nomPrenom;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getExamen(): ?string
    {
        return $this->examen;
    }

    public function setExamen(string $examen): self
    {
        $this->examen = $examen;

        return $this;
    }

    public function getExamenNom(): ?string
    {
        return $this->examenNom;
    }

    public function setExamenNom(string $examenNom): self
    {
        $this->examenNom = $examenNom;

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

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getNomSession(): ?string
    {
        return $this->nomSession;
    }

    public function setNomSession(string $nomSession): self
    {
        $this->nomSession = $nomSession;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

}
