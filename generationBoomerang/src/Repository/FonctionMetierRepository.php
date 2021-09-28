<?php

namespace App\Repository;

use App\Entity\FonctionMetier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FonctionMetier|null find($id, $lockMode = null, $lockVersion = null)
 * @method FonctionMetier|null findOneBy(array $criteria, array $orderBy = null)
 * @method FonctionMetier[]    findAll()
 * @method FonctionMetier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FonctionMetierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FonctionMetier::class);
    }

    // /**
    //  * @return FonctionMetier[] Returns an array of FonctionMetier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FonctionMetier
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
