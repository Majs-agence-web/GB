<?php

namespace App\Repository;

use App\Entity\OffreVisMaVie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OffreVisMaVie|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreVisMaVie|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreVisMaVie[]    findAll()
 * @method OffreVisMaVie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreVisMaVieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreVisMaVie::class);
    }

    // /**
    //  * @return OffreVisMaVie[] Returns an array of OffreVisMaVie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OffreVisMaVie
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
