<?php
namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Adresse;
use App\Entity\Commentaires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        

        for($i = 1 ; $i <= 10 ; $i++){
            $user = new User();
            $hash= $this->encoder->encodePassword($user, '123456');
            $user   ->setFirstName($faker->firstName())                        
                    ->setLastName($faker->lastname(null))
                    ->setFilename("avatarVide.jpg")
                    ->setDescription($faker->paragraph(2))
                    ->setEmail($faker->email())
                    ->setRoles(["ROLE_USER"])
                    ->setPassword($hash)
                    ->setActivationToken("");
            $adresse = $this->createAdresse($i);            
            $manager-> persist($adresse);
            $adresse-> adduser($user);
            $user-> setAdresse($adresse);
            $manager-> persist($user);
        }

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
            $adresse = $this->createAdresse($i);            
            $manager-> persist($adresse);
            $adresse-> adduser($user);
            $user-> setAdresse($adresse);
            $manager-> persist($user);

        $manager->flush();
    }

    // Fixture pour générer les adresse
    public function createAdresse(int $i){
        $faker = Faker\Factory::create('fr_FR');
        $adresse = new Adresse();
        $adresse    -> setNumeroRue($faker->buildingNumber())
                    -> setNomrue($faker->streetName())
                    -> setCodePostal($faker->buildingNumber())
                    -> setVille($faker->city())
                    -> setRegion($faker->country());
        return $adresse;
    }

  


}

