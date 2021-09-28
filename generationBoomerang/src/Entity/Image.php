<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageRepository;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 *  @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable()
 */
class Image
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
     * @Assert\Length(min=5, minMessage="Image obligatoire", allowEmptyString=false,
     * max=255)
     */
    private $filename;

    /**
     * gestion de l'upload de la photo
     * 
     * @var File|null
     * @Assert\Image(
     *      mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/jpeg"}
     * )
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="filename")
     */
    private $imageFile;


    /**
     * @ORM\ManyToOne(targetEntity=Articles::class, inversedBy="images",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function __toString(){
    //     return $this->article;
    // }

    /**
     * Get undocumented variable
     *
     * @return  String|null
     */ 

    public function getFilename(): ?string
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

    public function setFilename(?string $filename): self
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
            $this->UpdatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function getArticle(): ?Articles
    {
        return $this->article;
    }

    public function setArticle(?Articles $article): self
    {
        $this->article = $article;

        return $this;
    }
}
