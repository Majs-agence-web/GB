<?php

namespace App\Entity;

use App\Repository\UserFormationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserFormationRepository::class)
 */
class UserFormation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $anneePromo;


    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="userFormations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @ORM\ManyToOne(targetEntity=Annuaire::class, inversedBy="userFormations", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $annuaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnneePromo(): ?string
    {
        return $this->anneePromo;
    }

    public function setAnneePromo(string $anneePromo): self
    {
        $this->anneePromo = $anneePromo;

        return $this;
    }


    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

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
