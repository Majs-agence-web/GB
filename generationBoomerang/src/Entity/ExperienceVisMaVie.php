<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ExperienceVisMaVieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ExperienceVisMaVieRepository::class)
 */
class ExperienceVisMaVie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * 
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomEntreprise;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $villeEntreprise;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $DateFin;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="experienceVisMaVies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Metier::class, inversedBy="experienceVisMaVies", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $metier;

    /**
     * @ORM\OneToMany(targetEntity=OffreVisMaVie::class, mappedBy="VisMaVie", orphanRemoval=true)
     */
    private $offreVisMaVies;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=26)
     * @Assert\NotBlank(message="Vous devez renseignerun titre pour votre annonce")
     * @Assert\Length(min=5, minMessage="Minimum 5 caractères pour le titre de l'annonce", allowEmptyString=false,
     * max=26, maxMessage="Le titre ne doit as faire plus de 28 caratères (pas plus de 28 caractères)")
     */
    private $TitreAnnonce;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephone;

    public function __construct()
    {
        $this->offreVisMaVies = new ArrayCollection();
    }

    /**
     * permet d'initialiser le slug
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return  void
     */
    public function initializeSlug(){
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug =$slugify->slugify($this->titreAnnonce);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): self
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    public function getVilleEntreprise(): ?string
    {
        return $this->villeEntreprise;
    }

    public function setVilleEntreprise(string $villeEntreprise): self
    {
        $this->villeEntreprise = $villeEntreprise;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    public function setMetier(?Metier $metier): self
    {
        $this->metier = $metier;

        return $this;
    }

    /**
     * @return Collection|OffreVisMaVie[]
     */
    public function getOffreVisMaVies(): Collection
    {
        return $this->offreVisMaVies;
    }

    public function addOffreVisMaVy(OffreVisMaVie $offreVisMaVy): self
    {
        if (!$this->offreVisMaVies->contains($offreVisMaVy)) {
            $this->offreVisMaVies[] = $offreVisMaVy;
            $offreVisMaVy->setVisMaVie($this);
        }

        return $this;
    }

    public function removeOffreVisMaVy(OffreVisMaVie $offreVisMaVy): self
    {
        if ($this->offreVisMaVies->contains($offreVisMaVy)) {
            $this->offreVisMaVies->removeElement($offreVisMaVy);
            // set the owning side to null (unless already changed)
            if ($offreVisMaVy->getVisMaVie() === $this) {
                $offreVisMaVy->setVisMaVie(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitreAnnonce(): ?string
    {
        return $this->TitreAnnonce;
    }

    public function setTitreAnnonce(string $TitreAnnonce): self
    {
        $this->TitreAnnonce = $TitreAnnonce;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
