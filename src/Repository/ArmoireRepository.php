<?php

namespace App\Repository;

use App\Entity\Armoire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Armoire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Armoire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Armoire[]    findAll()
 * @method Armoire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArmoireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Armoire::class);
    }

    // /**
    //  * @return Armoire[] Returns an array of Armoire objects
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
    public function findOneBySomeField($value): ?Armoire
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('a');

        if($search_input!=""){
            $query->where('a.name LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('a.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }
}
