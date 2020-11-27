<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('i');

        if($search_input!=""){
            $query->join('i.sousItem','s')
           
                  ->where('i.label LIKE :word or s.label LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('i.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    public function findUserDetails($id){

        return $this->createQueryBuilder('i')
                    ->select(['i','s'])
                    ->join('i.sousItem','s')
                    ->where('s.id =:id')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getArrayResult();

    }
}
