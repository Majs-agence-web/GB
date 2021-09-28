<?php

namespace App\Entity;

use Serializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable()
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"email"},
 * message="cette adresse email a déjà été utilisée, merci de la modifier"
 * )
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * permet de gérer le nom de l'image
     *
     * @var String|null
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, minMessage="Image obligatoire", allowEmptyString=true,
     * max=255)
     *
     */
    private $filename;

     /**
     * gestion de l'upload de la photo
     * 
     * @var File|null
     * @Assert\Image(
     *      mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/gif"}
     * )
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Vous devez renseigner votre email")
     * @Assert\Email(message="Veuillez renseigner un email valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner un mot de passe")
     * @Assert\Regex(
     * pattern     = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{6,}$#",
     * htmlPattern = "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{6,}$#",
     * )
     */
    private $password;

    /**
     *
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas correctement confirmé votre mot de passe ")
     */
    public $passwordConfirm;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez renseigner votre prénom")
     * @Assert\Length(min=2, minMessage="Minimum 2 caractères pour le prénom", allowEmptyString=false,
     * max=50, maxMessage="le prénom est trop long (pas plus de 50 caractères)")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre prénom ne doit pas contenir de chiffre")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez renseigner votre nom")
     * @Assert\Length(min=2, minMessage="Minimum 2 caractères pour le nom", allowEmptyString=false,
     * max=50, maxMessage="le nom est trop long (pas plus de 50 caractères)")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Votre nom ne doit pas contenir de chiffre")
     */
    private $lastName;

    
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Adresse::class, inversedBy="users", cascade={"persist"})
     * 
     */
    private $Adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="user", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Liker::class, mappedBy="user", orphanRemoval=true)
     */
    private $likers;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="author", orphanRemoval=true)
     */
    private $articles;

    // /**
    //  * @ORM\OneToMany(targetEntity=UserFormation::class, mappedBy="user", orphanRemoval=true)
    //  */
    // private $userFormations;

    /**
     * @ORM\OneToMany(targetEntity=Temoignage::class, mappedBy="user", orphanRemoval=true)
     */
    private $temoignages;

    /**
     * @ORM\OneToMany(targetEntity=ExperienceVisMaVie::class, mappedBy="User", orphanRemoval=true)
     */
    private $experienceVisMaVies;

    /**
     * @ORM\OneToMany(targetEntity=OffreVisMaVie::class, mappedBy="user", orphanRemoval=true)
     */
    private $offreVisMaVies;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="user",cascade={"persist"})
     */
    private $entreprise;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $civilite;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthdate;

    // /**
    //  * @ORM\OneToOne(targetEntity=Abonnement::class, cascade={"persist", "remove"}, orphanRemoval=true )
    //  * @ORM\JoinColumn(nullable=false)
    //  */
    // private $abonnement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $compte;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="user", orphanRemoval=true, cascade={"persist"} )
     * 
     */
    private $documents;

    private $mailer;


    public function getFullName(){
        return "{$this->firstName} {$this->lastName}";

    }

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->likers = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->userFormations = new ArrayCollection();
        $this->temoignages = new ArrayCollection();
        $this->experienceVisMaVies = new ArrayCollection();
        $this->offreVisMaVies = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of VerifyPassword
     */ 
    public function getVerifyPassword()
    {
        return $this->VerifyPassword;
    }

    /**
     * Set the value of VerifyPassword
     *
     * @return  self
     */ 
    public function setVerifyPassword($VerifyPassword)
    {
        $this->VerifyPassword = $VerifyPassword;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->Adresse;
    }

    public function setAdresse(?Adresse $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function __toString(){
        return $this->email;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Liker[]
     */
    public function getLikers(): Collection
    {
        return $this->likers;
    }

    public function addLiker(Liker $liker): self
    {
        if (!$this->likers->contains($liker)) {
            $this->likers[] = $liker;
            $liker->setUser($this);
        }

        return $this;
    }

    public function removeLiker(Liker $liker): self
    {
        if ($this->likers->contains($liker)) {
            $this->likers->removeElement($liker);
            // set the owning side to null (unless already changed)
            if ($liker->getUser() === $this) {
                $liker->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

 // Gestion de l'avatar ////////////////////////////////////////
    /**
     * Get undocumented variable
     *
     * @return  String|null
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set undocumented variable
     *
     * @param  String|null  $filename  Undocumented variable
     *
     * @return  self
     */ 
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get gestion de l'upload de la photo
     *
     * @return  File|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set gestion de l'upload de la photo
     *
     * @param  File|null  $imageFile  gestion de l'upload de la photo
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile){
            $this->updated_at = new \DateTime('now');
        }

        return $this;
    }



    // /**
    //  * @return Collection|UserFormation[]
    //  */
    // public function getUserFormations(): Collection
    // {
    //     return $this->userFormations;
    // }

    // public function addUserFormation(UserFormation $userFormation): self
    // {
    //     if (!$this->userFormations->contains($userFormation)) {
    //         $this->userFormations[] = $userFormation;
    //         $userFormation->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeUserFormation(UserFormation $userFormation): self
    // {
    //     if ($this->userFormations->contains($userFormation)) {
    //         $this->userFormations->removeElement($userFormation);
    //         // set the owning side to null (unless already changed)
    //         if ($userFormation->getUser() === $this) {
    //             $userFormation->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getUpdatedAt(): ?\DateTimeInterface
 {
return $this->updated_at;
}

public function setUpdatedAt(\DateTimeInterface $updated_at): self
{
$this->updated_at = $updated_at;

 return $this;
}

public function serialize() {

return serialize(array(
$this->id,
$this->email,
$this->password,
));

}

public function unserialize($serialized) {

list (
$this->id,
$this->email,
$this->password,
) = unserialize($serialized);
}

/**
 * @return Collection|Temoignage[]
 */
public function getTemoignages(): Collection
{
    return $this->temoignages;
}

public function addTemoignage(Temoignage $temoignage): self
{
    if (!$this->temoignages->contains($temoignage)) {
        $this->temoignages[] = $temoignage;
        $temoignage->setUser($this);
    }

    return $this;
}

public function removeTemoignage(Temoignage $temoignage): self
{
    if ($this->temoignages->contains($temoignage)) {
        $this->temoignages->removeElement($temoignage);
        // set the owning side to null (unless already changed)
        if ($temoignage->getUser() === $this) {
            $temoignage->setUser(null);
        }
    }

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
        $experienceVisMaVy->setUser($this);
    }

    return $this;
}

public function removeExperienceVisMaVy(ExperienceVisMaVie $experienceVisMaVy): self
{
    if ($this->experienceVisMaVies->contains($experienceVisMaVy)) {
        $this->experienceVisMaVies->removeElement($experienceVisMaVy);
        // set the owning side to null (unless already changed)
        if ($experienceVisMaVy->getUser() === $this) {
            $experienceVisMaVy->setUser(null);
        }
    }

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
        $offreVisMaVy->setUser($this);
    }

    return $this;
}

public function removeOffreVisMaVy(OffreVisMaVie $offreVisMaVy): self
{
    if ($this->offreVisMaVies->contains($offreVisMaVy)) {
        $this->offreVisMaVies->removeElement($offreVisMaVy);
        // set the owning side to null (unless already changed)
        if ($offreVisMaVy->getUser() === $this) {
            $offreVisMaVy->setUser(null);
        }
    }

    return $this;
}

public function getEntreprise(): ?Entreprise
{
    return $this->entreprise;
}

public function setEntreprise(?Entreprise $entreprise): self
{
    $this->entreprise = $entreprise;

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

public function getCivilite(): ?string
{
    return $this->civilite;
}

public function setCivilite(string $civilite): self
{
    $this->civilite = $civilite;

    return $this;
}

public function getBirthdate(): ?\DateTimeInterface
{
    return $this->birthdate;
}

public function setBirthdate(\DateTimeInterface $birthdate): self
{
    $this->birthdate = $birthdate;

    return $this;
}

public function getAbonnement(): ?Abonnement
{
    return $this->abonnement;
}

public function setAbonnement(Abonnement $abonnement): self
{
    $this->abonnement = $abonnement;

    return $this;
}

public function getCompte(): ?string
{
    return $this->compte;
}

public function setCompte(string $compte): self
{
    $this->compte = $compte;
    // if($compte === "valider"){
    //     $message = (new \Swift_Message('Compte validé'))
    //     ->setFrom('generation.boomerang2020@gmail.com')
    //     ->setTo($this->email)
    //     ->setBody(
    //         "Votre compte a été validé, vous pouvez maintenant vous rendre sur https://generation-boomerang.com et profiter de toutes nos fonctionnalités"
    //     );
    //     $this->mailer->send($message);
    // }
    return $this;
}

/**
 * @return Collection|Document[]
 */
public function getDocuments(): Collection
{
    return $this->documents;
}

public function addDocument(Document $document): self
{
    if (!$this->documents->contains($document)) {
        $this->documents[] = $document;
        $document->setUser($this);
    }

    return $this;
}

public function removeDocument(Document $document): self
{
    if ($this->documents->contains($document)) {
        $this->documents->removeElement($document);
        // set the owning side to null (unless already changed)
        if ($document->getUser() === $this) {
            $document->setUser(null);
        }
    }

    return $this;
}
}