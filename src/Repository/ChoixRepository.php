<?php

namespace App\Repository;

use App\Entity\Choix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
/**
 * @method Choix|null find($id, $lockMode = null, $lockVersion = null)
 * @method Choix|null findOneBy(array $criteria, array $orderBy = null)
 * @method Choix[]    findAll()
 * @method Choix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Choix::class);
    }

    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('c');

        if($search_input!=""){
            $query->where('c.label LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('c.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    public function findChoix(){

        $query= $this->createQueryBuilder('c');

        $query->select("PARTIAL c.{id,  label}");
              
             

        return $query->orderBy('c.id', 'DESC')
                        ->getQuery()
                        ->getResult(AbstractQuery::HYDRATE_ARRAY);

    }
}
