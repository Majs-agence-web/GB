<?php

namespace App\Entity;

use App\Repository\MetierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MetierRepository::class)
 */
class Metier
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
    private $nomMetier;

    /**
     * @ORM\ManyToOne(targetEntity=FonctionMetier::class, inversedBy="metier", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $fonctionMetier;

    /**
     * @ORM\OneToMany(targetEntity=ExperienceVisMaVie::class, mappedBy="metier", orphanRemoval=true)
     */
    private $experienceVisMaVies;

    public function __construct()
    {
        $this->experienceVisMaVies = new ArrayCollection();
    }

    public function __toString(){
        return $this->nomMetier;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMetier(): ?string
    {
        return $this->nomMetier;
    }

    public function setNomMetier(string $nomMetier): self
    {
        $this->nomMetier = $nomMetier;

        return $this;
    }

    public function getFonctionMetier(): ?FonctionMetier
    {
        return $this->fonctionMetier;
    }

    public function setFonctionMetier(?FonctionMetier $fonctionMetier): self
    {
        $this->fonctionMetier = $fonctionMetier;

        return $this;
    }

    /**
     * @return Collection|ExperienceVisMaVie[]
     */
    public function getExperienceVisMaVies(): Collection
    {
        return $this->experienceVisMaVies;
    }

    public function addExperienceVisMaVy(ExperienceVisMaVie $experienceVisMaVy): self
    {
        if (!$this->experienceVisMaVies->contains($experienceVisMaVy)) {
            $this->experienceVisMaVies[] = $experienceVisMaVy;
            $experienceVisMaVy->setMetier($this);
        }

        return $this;
    }

    public function removeExperienceVisMaVy(ExperienceVisMaVie $experienceVisMaVy): self
    {
        if ($this->experienceVisMaVies->contains($experienceVisMaVy)) {
            $this->experienceVisMaVies->removeElement($experienceVisMaVy);
            // set the owning side to null (unless already changed)
            if ($experienceVisMaVy->getMetier() === $this) {
                $experienceVisMaVy->setMetier(null);
            }
        }

        return $this;
    }
}
