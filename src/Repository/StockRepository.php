<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

  
    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('s');

        if($search_input!=""){
            $query->where('s.nomProduit LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('s.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }
    public function findStock(){

        $query= $this->createQueryBuilder('s');

        $query->select("PARTIAL s.{id, nomProduit, quantiteCasse}");

        return $query->orderBy('s.id', 'DESC')
                        ->getQuery()
                        ->getResult(AbstractQuery::HYDRATE_ARRAY);

    }
}
