<?php

namespace App\Repository;

use App\Entity\Fiche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fiche|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fiche|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fiche[]    findAll()
 * @method Fiche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fiche::class);
    }


    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('f');

        if($search_input!=""){
            $query->where('f.label LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('f.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    public function findFicheByType($id_type){

        $query= $this->createQueryBuilder('f')
                     ->where('f.type =:type')
                     ->setParameter('type',$id_type)
                     ->getQuery()
                     ->getArrayResult();

        return $query;

    }

}
