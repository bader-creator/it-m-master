<?php

namespace App\Repository;

use App\Entity\TracabilityReponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TracabilityReponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method TracabilityReponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method TracabilityReponse[]    findAll()
 * @method TracabilityReponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TracabilityReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TracabilityReponse::class);
    }

    // /**
    //  * @return TracabilityReponse[] Returns an array of TracabilityReponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TracabilityReponse
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
