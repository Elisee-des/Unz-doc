<?php

namespace App\Entity;

use App\Repository\PropositionCorrectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropositionCorrectionRepository::class)]
class PropositionCorrection
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
    private ?string $correction = null;

    #[ORM\Column(length: 255)]
    private ?string $correctionNom = null;

    #[ORM\Column(length: 255)]
    private ?string $nomSession = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'propositionCorrections')]
    private ?Module $module = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

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

    public function getCorrection(): ?string
    {
        return $this->correction;
    }

    public function setCorrection(string $correction): self
    {
        $this->correction = $correction;

        return $this;
    }

    public function getCorrectionNom(): ?string
    {
        return $this->correctionNom;
    }

    public function setCorrectionNom(string $correctionNom): self
    {
        $this->correctionNom = $correctionNom;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
