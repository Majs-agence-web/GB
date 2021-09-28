<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdresseRepository::class)
 */
class Adresse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(min=1, minMessage="Minimum un caractères pour le numero de rue", allowEmptyString=false,
     * max=10, maxMessage="Numero de rue trop long (pas plus de 10 caractères)")
     *
     */
    private $numeroRue;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=3, minMessage="Minimum trois caractères pour le nom de rue", allowEmptyString=false,
     * max=50, maxMessage="Nom de rue trop long (pas plus de 50 caractères)")
     *
     */
    private $nomRue;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\NotBlank(message="vous devez renseigner votre code postal")
     * @Assert\Length(min=5, minMessage="Minimum cinq chiffres pour le code postal", allowEmptyString=false,
     * max=5, maxMessage="Code postal incorrect (pas plus de 5 caractères)")
     *
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=3, minMessage="Nom de ville obligatoire", allowEmptyString=false,
     * max=50, maxMessage="Nom de ville incorrect (pas plus de 50 caractères)")
     *
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=3, minMessage="Nom de région obligatoire", allowEmptyString=false,
     * max=50, maxMessage="Nom de region incorrect (pas plus de 50 caractères)")
     *
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="Adresse")
     * 
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Entreprise::class, mappedBy="adresse")
     * 
     */
    private $entreprises;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroRue(): ?string
    {
        return $this->numeroRue;
    }

    public function setNumeroRue(string $numeroRue): self
    {
        $this->numeroRue = $numeroRue;

        return $this;
    }

    public function getNomRue(): ?string
    {
        return $this->nomRue;
    }

    public function setNomRue(string $nomRue): self
    {
        $this->nomRue = $nomRue;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAdresse($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getAdresse() === $this) {
                $user->setAdresse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Entreprise[]
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->setAdresse($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->entreprises->contains($entreprise)) {
            $this->entreprises->removeElement($entreprise);
            // set the owning side to null (unless already changed)
            if ($entreprise->getAdresse() === $this) {
                $entreprise->setAdresse(null);
            }
        }

        return $this;
    }
}
