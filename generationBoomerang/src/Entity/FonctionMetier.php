<?php

namespace App\Entity;

use App\Repository\FonctionMetierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FonctionMetierRepository::class)
 */
class FonctionMetier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomFonction;

    /**
     * @ORM\OneToMany(targetEntity=Metier::class, mappedBy="fonctionMetier", orphanRemoval=true)
     */
    private $metier;

    /**
     * @ORM\ManyToOne(targetEntity=Annuaire::class, inversedBy="fonctionMetier")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annuaire;

    public function __construct()
    {
        $this->metier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFonction(): ?string
    {
        return $this->nomFonction;
    }

    public function setNomFonction(string $nomFonction): self
    {
        $this->nomFonction = $nomFonction;

        return $this;
    }

    /**
     * @return Collection|Metier[]
     */
    public function getMetier(): Collection
    {
        return $this->metier;
    }

    public function addMetier(Metier $metier): self
    {
        if (!$this->metier->contains($metier)) {
            $this->metier[] = $metier;
            $metier->setFonctionMetier($this);
        }

        return $this;
    }

    public function removeMetier(Metier $metier): self
    {
        if ($this->metier->contains($metier)) {
            $this->metier->removeElement($metier);
            // set the owning side to null (unless already changed)
            if ($metier->getFonctionMetier() === $this) {
                $metier->setFonctionMetier(null);
            }
        }

        return $this;
    }

    public function getAnnuaire(): ?Annuaire
    {
        return $this->annuaire;
    }

    public function setAnnuaire(?Annuaire $annuaire): self
    {
        $this->annuaire = $annuaire;

        return $this;
    }
}
