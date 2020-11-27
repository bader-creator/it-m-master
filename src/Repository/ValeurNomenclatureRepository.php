<?php

namespace App\Repository;

use App\Entity\ValeurNomenclature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ValeurNomenclature|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValeurNomenclature|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValeurNomenclature[]    findAll()
 * @method ValeurNomenclature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValeurNomenclatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValeurNomenclature::class);
    }

        public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('v');

        if($search_input!=""){
            $query->join('v.typeNomenclature','t')
                  ->where('v.name LIKE :word  LIKE :word or t.name LIKE :word')
                 ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('v.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    
}
