<?php
namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Articles;
use Cocur\Slugify\Slugify;
use App\Entity\Commentaires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CommentairesFixtures extends Fixture{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager){

        for($i = 1 ; $i <= 10 ; $i++){
            //fixtures pour générer des commentaires
           
                $commentaire = new Commentaires();
                $commentaire ->setContenu("test sur le contenu")
                            ->setCreatedAt(new \DateTime('2020-06-22'))
                            ->setActif(1)
                            ->setRgpd(1);
                $user= $this->createUser();
                $manager->persist($user);
                $user->addCommentaire($commentaire);
                $commentaire->setUser($user);
                $article= $this->createArticle($i);
                $manager->persist($article);
                $article->addCommentaire($commentaire);
                $commentaire->setArticle($article);
                $manager-> persist($commentaire);


        }   
    }

    public function createUser(){

        $user = new User();
            $hash= $this->encoder->encodePassword($user,'Internet1!');
            $user   ->setFirstName("Admin")                        
                    ->setLastName("Admin")
                    ->setFilename("avatarVide.jpg")
                    ->setDescription("Developpeur Web")
                    ->setEmail("admin@gmail.com")
                    ->setRoles(["ROLE_ADMIN"])
                    ->setPassword($hash)
                    ->setActivationToken("");
                   
        return $user;
    }

    public function CreateArticle(){

        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        $article = new Articles();
        $titreArticle = $faker->sentence();
            $article ->setTitreArticle($titreArticle)
                      ->setContenuArticle($faker->paragraph( 5, false))
                      ->setFilename("1efqCuh_NI.jpg")
                      ->setVideo($faker->mimeType())
                      ->setCreatedAT(new \DateTime())
                      ->setUpdatedAt(new \DateTime())
                      ->setSlug($slugify->slugify($titreArticle));

        return $article;
    }


}


 