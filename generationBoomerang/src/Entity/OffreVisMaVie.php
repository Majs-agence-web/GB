<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffreVisMaVieRepository;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=OffreVisMaVieRepository::class)
 * @Vich\Uploadable()
 * @ORM\HasLifecycleCallbacks()
 */
class OffreVisMaVie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptionMotivation;

    /**
     * Permet de gérer le nom du CV
     * @var String|null
     * @ORM\Column(type="string", length=255)
     */
    private $UploadCV;

     /**
     * gestion de l'upload de la photo
     * 
     * @var File|null
     * 
     * @Vich\UploadableField(mapping="user_CV", fileNameProperty="UploadCV")
     */
    private $CvFile;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="offreVisMaVies",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=ExperienceVisMaVie::class, inversedBy="offreVisMaVies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $VisMaVie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionMotivation(): ?string
    {
        return $this->descriptionMotivation;
    }

    public function setDescriptionMotivation(string $descriptionMotivation): self
    {
        $this->descriptionMotivation = $descriptionMotivation;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  String|null
     */ 
    public function getUploadCV()
    {
        return $this->UploadCV;
    }

     /**
     * Set undocumented variable
     *
     * @param  String|null  $UploadCV  Undocumented variable
     *
     * @return  self
     */ 
    public function setUploadCV($UploadCV)
    {
        $this->UploadCV = $UploadCV;

        return $this;
    }
    /**
     * Get gestion de l'upload de la photo
     *
     * @return  File|null
     */ 
    public function getCvFile()
    {
        return $this->CvFile;
    }

    /**
     * Set gestion de l'upload de la photo
     *
     * @param  File|null  $CvFile  gestion de l'upload de la photo
     *
     * @return  self
     */ 
    public function setCvFile($CvFile)
    {
        $this->CvFile = $CvFile;
        

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVisMaVie(): ?ExperienceVisMaVie
    {
        return $this->VisMaVie;
    }

    public function setVisMaVie(?ExperienceVisMaVie $VisMaVie): self
    {
        $this->VisMaVie = $VisMaVie;

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
