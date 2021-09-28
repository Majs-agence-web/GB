<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable()
 * @UniqueEntity(
 * fields={"titreArticle"},
 * message="un autre article possède déjà ce titre, merci de le modifier"
 * ) 

 */
class Articles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    

    /**
     * @ORM\Column(type="string", length=50)
     * 
     *
     */
    private $titreArticle;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=50, minMessage="Le contenu de l'article doit faire plus de 50 caratères", allowEmptyString=false)
     *
     */
    private $ContenuArticle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
     
    private $slug;

   
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video;

    
    /**
     * Undocumented variable
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="Article", orphanRemoval=true)
     *
     */
 
    private $commentaires;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $UpdatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Liker::class, mappedBy="article", orphanRemoval=true)
     */
    private $likers;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="article", orphanRemoval=true,cascade={"persist"})
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     */
    private $accroche;

    /**
     * @ORM\Column(type="text", length=700)
     */
    private $intro;

    /**
     * @ORM\ManyToOne(targetEntity=Pole::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $pole;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->likers = new ArrayCollection();
        $this->images = new ArrayCollection();
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
            $this->slug =$slugify->slugify($this->titreArticle);
        }
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreArticle(): ?string
    {
        return $this->titreArticle;
    }

    public function setTitreArticle(string $titreArticle): self
    {
        $this->titreArticle = $titreArticle;

        return $this;
    }

    public function getContenuArticle(): ?string
    {
        return $this->ContenuArticle;
    }

    public function setContenuArticle(string $ContenuArticle): self
    {
        $this->ContenuArticle = $ContenuArticle;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

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
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }

        return $this;
    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

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
            $liker->setArticle($this);
        }

        return $this;
    }

    public function removeLiker(Liker $liker): self
    {
        if ($this->likers->contains($liker)) {
            $this->likers->removeElement($liker);
            // set the owning side to null (unless already changed)
            if ($liker->getArticle() === $this) {
                $liker->setArticle(null);
            }
        }

        return $this;
    }

      /**
     *
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user) : bool {
        foreach($this->likers as $like) {
            if($like->getUser() === $user) return true;
        }

        return false;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setArticle($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }

    public function getAccroche(): ?string
    {
        return $this->accroche;
    }

    public function setAccroche(?string $accroche): self
    {
        $this->accroche = $accroche;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    public function getPole(): ?Pole
    {
        return $this->pole;
    }

    public function setPole(?Pole $pole): self
    {
        $this->pole = $pole;

        return $this;
    }
}
