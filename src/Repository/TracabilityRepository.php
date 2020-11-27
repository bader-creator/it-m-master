<?php

namespace App\Repository;

use App\Entity\Tracability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tracability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tracability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tracability[]    findAll()
 * @method Tracability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TracabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tracability::class);
    }

    // /**
    //  * @return Tracability[] Returns an array of Tracability objects
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
    public function findOneBySomeField($value): ?Tracability
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
     
    public function findBySearchInput($search_input,$id){

        $query= $this->createQueryBuilder('t')
                    ->join('t.stock','s')
                    ->where('s.id =:id')
                    ->setParameter('id',$id);

        if($search_input!=""){
            $query->andwhere('t.stock.nomProduit or t.typeAction tIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('t.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    // public function findByStock($id){

    //     return $this->createQueryBuilder('t')
    //                 ->select(['t','s'])
    //                 ->join('t.stock','s')
    //                 ->where('t.id =:id')
    //                 ->setParameter('id',$id)
    //                 ->getQuery()
    //                 ->getArrayResult();
                   
    // }

    public function finddTraceByStock($stock)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.stock = :val')
            ->setParameter('val', $stock)
            ->getQuery()
            ->getArrayResult();
    }
}
