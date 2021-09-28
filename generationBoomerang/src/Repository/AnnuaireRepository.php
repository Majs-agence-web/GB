<?php

namespace App\Repository;

use App\Entity\Annuaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @method Annuaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annuaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annuaire[]    findAll()
 * @method Annuaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnuaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annuaire::class);
    }

    public function search(array $request) 
    {
        $entityManager = $this->getEntityManager();
        $query = "";

        foreach($request as $key => $value)
        {
                if($value != "")
                {
                    if($key == "firstName" || $key == "lastName" )
                    {
                        $query = $query.'u.'.$key." LIKE '".$value."' OR ";
                    }else
                    {
                        $str = explode(",",$value);
                        if(sizeOF($str) > 1)
                        {
                            for($i=0;$i<sizeOF($str);$i++)
                            {
                                $query = $query.'u.'.$key." LIKE '".$str[$i]."' OR ";
                            }
                        }else
                        {
                            $query = $query.'u.'.$key." LIKE '".$value."' OR ";
                        }
                    }

        }
        $dql = 'SELECT a FROM App\Entity\User u INNER JOIN App\Entity\Annuaire a WITH u.id = a.user WHERE '.$query.'';
        $dql = rtrim($dql,"OR ");
        $query = $this->getEntityManager()->createQuery($dql);
        $donnees = $query->getResult();
        return $donnees;
    }
}


    public function searchAdvanced(array $request) 
    {
        $entityManager = $this->getEntityManager();
        $query = "";

            foreach($request as $key => $value)
            {   
                if($value != ""){
                    if($key == "firstName" || $key == "lastName")
                    {
                        $query = $query.'u.'.$key." LIKE '".$value."' OR ";
                    }else
                    {                                              
                        $query = $query.'a.'.$key." LIKE '".$value."' OR ";                           
                    }
                   
                }

        }
        $dql = 'SELECT a FROM App\Entity\User u INNER JOIN App\Entity\Annuaire a WITH u.id = a.user WHERE '.$query.'';
        $dql = rtrim($dql,"OR ");
        $query = $this->getEntityManager()->createQuery($dql);
        $donnees = $query->getResult();
        return $donnees;
    }

   


    // /**
    //  * @return Annuaire[] Returns an array of Annuaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annuaire
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}