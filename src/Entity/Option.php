<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[ORM\Table(name: '`option`')]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'options')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Licence $licence = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\OneToMany(mappedBy: 'optionn', targetEntity: Annee::class)]
    private Collection $annees;

    public function __construct()
    {
        $this->annees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->nom;
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

    public function getLicence(): ?Licence
    {
        return $this->licence;
    }

    public function setLicence(?Licence $licence): self
    {
        $this->licence = $licence;

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

    /**
     * @return Collection<int, Annee>
     */
    public function getAnnees(): Collection
    {
        return $this->annees;
    }

    public function addAnnee(Annee $annee): self
    {
        if (!$this->annees->contains($annee)) {
            $this->annees->add($annee);
            $annee->setOptionn($this);
        }

        return $this;
    }

    public function removeAnnee(Annee $annee): self
    {
        if ($this->annees->removeElement($annee)) {
            // set the owning side to null (unless already changed)
            if ($annee->getOptionn() === $this) {
                $annee->setOptionn(null);
            }
        }

        return $this;
    }
}
