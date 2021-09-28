<?php

namespace App\Entity;

use App\Repository\SecteurActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SecteurActiviteRepository::class)
 */
class SecteurActivite
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
    private $nomSecteurActivite;

    /**
     * @ORM\OneToMany(targetEntity=Activite::class, mappedBy="secteurActivite", orphanRemoval=true)
     */
    private $activite;

    /**
     * @ORM\ManyToOne(targetEntity=Annuaire::class, inversedBy="secteurActivite")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annuaire;

    public function __construct()
    {
        $this->activite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSecteurActivite(): ?string
    {
        return $this->nomSecteurActivite;
    }

    public function setNomSecteurActivite(string $nomSecteurActivite): self
    {
        $this->nomSecteurActivite = $nomSecteurActivite;

        return $this;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getActivite(): Collection
    {
        return $this->activite;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activite->contains($activite)) {
            $this->activite[] = $activite;
            $activite->setSecteurActivite($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activite->contains($activite)) {
            $this->activite->removeElement($activite);
            // set the owning side to null (unless already changed)
            if ($activite->getSecteurActivite() === $this) {
                $activite->setSecteurActivite(null);
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
