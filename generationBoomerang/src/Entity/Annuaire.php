<?php

namespace App\Entity;

use App\Repository\AnnuaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnnuaireRepository::class)
 */
class Annuaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fonction;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entreprise;


    /**
     * @ORM\OneToMany(targetEntity=UserFormation::class, mappedBy="annuaire", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $userFormations;

    /**
     * @ORM\OneToMany(targetEntity=SecteurActivite::class, mappedBy="annuaire", orphanRemoval=true)
     */
    private $secteurActivite;

    /**
     * @ORM\OneToMany(targetEntity=FonctionMetier::class, mappedBy="annuaire", orphanRemoval=true)
     */
    private $fonctionMetier;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;


    public function __construct()
    {
        $this->userFormations = new ArrayCollection();
        $this->secteurActivite = new ArrayCollection();
        $this->fonctionMetier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): self
    {
        $this->entreprise = $entreprise;

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
            $userFormation->setAnnuaire($this);
        }

        return $this;
    }

    public function removeUserFormation(UserFormation $userFormation): self
    {
        if ($this->userFormations->contains($userFormation)) {
            $this->userFormations->removeElement($userFormation);
            // set the owning side to null (unless already changed)
            if ($userFormation->getAnnuaire() === $this) {
                $userFormation->setAnnuaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SecteurActivite[]
     */
    public function getSecteurActivite(): Collection
    {
        return $this->secteurActivite;
    }

    public function addSecteurActivite(SecteurActivite $secteurActivite): self
    {
        if (!$this->secteurActivite->contains($secteurActivite)) {
            $this->secteurActivite[] = $secteurActivite;
            $secteurActivite->setAnnuaire($this);
        }

        return $this;
    }

    public function removeSecteurActivite(SecteurActivite $secteurActivite): self
    {
        if ($this->secteurActivite->contains($secteurActivite)) {
            $this->secteurActivite->removeElement($secteurActivite);
            // set the owning side to null (unless already changed)
            if ($secteurActivite->getAnnuaire() === $this) {
                $secteurActivite->setAnnuaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FonctionMetier[]
     */
    public function getFonctionMetier(): Collection
    {
        return $this->fonctionMetier;
    }

    public function addFonctionMetier(FonctionMetier $fonctionMetier): self
    {
        if (!$this->fonctionMetier->contains($fonctionMetier)) {
            $this->fonctionMetier[] = $fonctionMetier;
            $fonctionMetier->setAnnuaire($this);
        }

        return $this;
    }

    public function removeFonctionMetier(FonctionMetier $fonctionMetier): self
    {
        if ($this->fonctionMetier->contains($fonctionMetier)) {
            $this->fonctionMetier->removeElement($fonctionMetier);
            // set the owning side to null (unless already changed)
            if ($fonctionMetier->getAnnuaire() === $this) {
                $fonctionMetier->setAnnuaire(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
