<?php

namespace App\Repository;

use App\Entity\TypeNomenclature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeNomenclature|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeNomenclature|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeNomenclature[]    findAll()
 * @method TypeNomenclature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeNomenclatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeNomenclature::class);
    }

     public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('t');

        if($search_input!=""){
            $query->where('t.name LIKE :word or t.code LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('t.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    
}
