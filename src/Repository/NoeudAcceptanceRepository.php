<?php

namespace App\Repository;

use App\Entity\NoeudAcceptance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NoeudAcceptance|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoeudAcceptance|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoeudAcceptance[]    findAll()
 * @method NoeudAcceptance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoeudAcceptanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoeudAcceptance::class);
    }

    // /**
    //  * @return NoeudAcceptance[] Returns an array of NoeudAcceptance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NoeudAcceptance
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('n');

        if($search_input!=""){
            $query->where('n.typeAcceptance LIKE :word  LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('n.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }
}
