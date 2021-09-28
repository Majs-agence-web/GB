<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $intitule;


    /**
     * @ORM\OneToMany(targetEntity=UserFormation::class, mappedBy="formation", orphanRemoval=true)
     */
    private $userFormations;

    /**
     * @ORM\ManyToOne(targetEntity=Domaine::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $domaine;

   

    public function __construct()
    {
        
        $this->userFormations = new ArrayCollection();
        $this->domaines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * @return Collection|UserFormation[]
     */
    public function getUserFormations(): Collection
    {
        return $this->userFormations;
    }

    public function addUserFormation(UserFormation $userFormation): self
    {
        if (!$this->userFormations->contains($userFormation)) {
            $this->userFormations[] = $userFormation;
            $userFormation->setFormation($this);
        }

        return $this;
    }

    public function removeUserFormation(UserFormation $userFormation): self
    {
        if ($this->userFormations->contains($userFormation)) {
            $this->userFormations->removeElement($userFormation);
            // set the owning side to null (unless already changed)
            if ($userFormation->getFormation() === $this) {
                $userFormation->setFormation(null);
            }
        }

        return $this;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    
}
