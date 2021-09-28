<?php

namespace App\Repository;

use App\Entity\ExperienceVisMaVie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExperienceVisMaVie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExperienceVisMaVie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExperienceVisMaVie[]    findAll()
 * @method ExperienceVisMaVie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperienceVisMaVieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExperienceVisMaVie::class);
    }

    public function search(string $request) 
    {
               
        $dql = "SELECT a FROM App\Entity\ExperienceVisMaVie as a WHERE a.villeEntreprise = '$request' " ;        
        $query = $this->getEntityManager()->createQuery($dql);
        $donnees = $query->getResult();
        return $donnees;
    }


    // /**
    //  * @return ExperienceVisMaVie[] Returns an array of ExperienceVisMaVie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExperienceVisMaVie
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
