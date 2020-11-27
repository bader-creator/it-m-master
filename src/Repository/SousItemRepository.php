<?php

namespace App\Repository;

use App\Entity\SousItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SousItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousItem[]    findAll()
 * @method SousItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousItem::class);
    }

    

    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('s');

        if($search_input!=""){
            $query->join('s.questions','q')
           
                  ->where('s.label LIKE :word or q.label LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('s.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    public function findUserDetails($id){

        return $this->createQueryBuilder('s')
                    ->select(['s','q'])
                    ->join('s.choix','q')
                    ->where('s.id =:id')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getArrayResult();

    }

}