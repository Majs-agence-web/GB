<?php

namespace App\DataFixtures;

use Faker;
use Author;
use App\Entity\User;
use App\Entity\Articles;
use Cocur\Slugify\Slugify;
use App\Entity\Commentaires;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticlesFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function createUser(){

        $user = new User();
            $hash= $this->encoder->encodePassword($user,'Internet2!');
            $user   ->setFirstName("adbk")                        
                    ->setLastName("adbk")
                    ->setFilename("avatarVide.jpg")
                    ->setDescription("Developpeur Web")
                    ->setEmail("adbk2@gmail.com")
                    ->setRoles(["ROLE_ADMIN"])
                    ->setPassword($hash)
                    ->setActivationToken("");
                   
        return $user;
    }


    public function load(ObjectManager $manager)
    {
        $user = new User;
        $users[]=$user;
        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();
        $user= $this->createUser();
        for ($i=1; $i < 30 ; $i++) { 
            $articles = new Articles();
            
            $titreArticle = $faker->sentence();
            $articles->setTitreArticle($titreArticle)
                    ->setContenuArticle($faker->paragraph( 5, false))
                    // ->setImage("/public/img/imageTest.jpg")
                    ->setFilename("1efqCuh_NI.jpg")
                    ->setVideo($faker->mimeType())
                    ->setCreatedAT(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setSlug($slugify->slugify($titreArticle));
            $manager-> persist($user);
            $user->addArticle($articles);
            $articles->setAuthor($user);

            $manager->persist($articles);
        }
        $manager->flush();
    }

    

}