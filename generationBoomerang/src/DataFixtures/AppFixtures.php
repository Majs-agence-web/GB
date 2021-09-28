<?php
namespace App\DataFixtures;


use Faker;
use App\Entity\User;
use App\Entity\Liker;
use App\Entity\Adresse;
use App\Entity\Articles;
use Cocur\Slugify\Slugify;
use App\Entity\Commentaires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    
    public static function getGroups(): array
    {
        return ['group1', 'group2'];
    }

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    
    public function load(ObjectManager $manager)
    {

        $user = new User();
        $hash = $this->encoder->encodePassword($user,'Internet1!');
        $user   ->setFirstName("Admin")                        
                ->setLastName("Admin")
                ->setFilename("avatarVide.jpg")
                ->setDescription("Developpeur Web")
                ->setEmail("admin@gmail.com")
                ->setRoles(["ROLE_ADMIN"])
                ->setPassword($hash)
                ->setActivationToken("");
        $adresse = $this->createAdresse();            
        $manager-> persist($adresse);
        $adresse-> adduser($user);
        $user-> setAdresse($adresse);
        $manager-> persist($user);

        $manager->flush();
    
      

        for($i=0; $i<10; $i++){
            $user=$this->createUser($i);
            $manager->persist($user);
       
            for($j=0; $j<mt_rand(3,3); $j++){
                $article=$this->createArticle($user);
                $manager->persist($article);
                for($k=0; $k<mt_rand(3,3); $k++){
                    $commentaire = $this->createCommentaire($user, $article);
                    $manager->persist($commentaire);
                }
                for($l=0; $l<mt_rand(3,3); $l++){
                    $liker = $this->createLiker($user, $article);
                    $manager->persist($liker);
                }
                // for($m=0; $m <mt_rand(1,1); $m++) {
                //     $image = $this->createImage($post);
                //     $manager->persist($image);
                // }
                // for($n=0; $n <mt_rand(3,3); $n++) {
                //     $notification = $this->createNotification($user);
                //     $manager->persist($notification);
                // }       

            }

        }

        $manager->flush();
    }


    public function createUser($i){
        $faker = Faker\Factory::create('fr_FR');

        $user    = new User();
        $hash    = $this->encoder->encodePassword($user, 'Internet1!');
        $user    ->setFirstName($faker->firstName())                        
                 ->setLastName($faker->lastname(null))
                 ->setFilename("avatarVide.jpg")
                 ->setDescription($faker->paragraph(2))
                 ->setEmail($faker->email())
                 ->setRoles(["ROLE_USER"])
                 ->setPassword($hash)
                 ->setActivationToken("");
        $adresse = $this->createAdresse();
        $adresse ->adduser($user);
        $user    ->setAdresse($adresse);       
                   
        return $user;
    }

    public function CreateArticle(User $user){

        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        $article      = new Articles();
        $titreArticle = $faker->sentence();
        $article      ->setTitreArticle($titreArticle)
                      ->setContenuArticle($faker->paragraph( 5, false))
                      ->setFilename("1efqCuh_NI.jpg")
                      ->setVideo($faker->mimeType())
                      ->setCreatedAT(new \DateTime())
                      ->setUpdatedAt(new \DateTime())
                      ->setSlug($slugify->slugify($titreArticle));
        $user         ->addArticle($article);
        $article      ->setAuthor($user);
           
        return $article;
    }

 
    // Fixture pour générer les adresse
    public function createAdresse(){
        $faker   = Faker\Factory::create('fr_FR');
        $adresse = new Adresse();
        $adresse -> setNumeroRue($faker->buildingNumber())
                 -> setNomrue($faker->streetName())
                 -> setCodePostal($faker->buildingNumber())
                 -> setVille($faker->city())
                 -> setRegion($faker->country());

        return $adresse;
    }

    
    private function createCommentaire($user, $article){
        $faker = Faker\Factory::create('FR-fr');
    
        $commentaire = new Commentaires();
        $commentaire ->setContenu($faker->sentence())
                     ->setCreatedAt(new \DateTime('2020-06-22'))
                     ->setActif(1)
                     ->setRgpd(1);
        $commentaire -> setUser($user);
        $commentaire -> setArticle($article);
       
        return $commentaire;

    }
   private function createLiker($user, $article){
        $liker = new Liker();
        $liker -> setArticle($article);
        $liker -> setUser($user);
        return $liker;
    } 



    // private function createImage ($post){
    //     $faker = Factory::create('FR-fr');
    //     $image = new Image();
    //     $image->setUrl($faker->imageUrl());
    //     $image ->setcaption($faker->sentence());  
    //     $image-> setAd($post);
    //     return $image;
    // }

    // private function createNotification(User $user){
    //     $faker = Factory::create('FR-fr');
    //     $notification = new Notification();
    //     $notification -> setUser($user);
    //     $notification->setNotif($faker->sentence());
    //     return $notification;
    // }
}