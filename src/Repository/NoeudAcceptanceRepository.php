<?php

namespace App\Repository;

use App\Entity\NoeudAcceptance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
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

    public function findQuestion($id){

        return $this->createQueryBuilder('r')
                    ->select(['r','c'])
                    ->join('r.choixReponse','c')
                    ->where('r.id =:id')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getResult();

    }

//     $query = $em->createQuery('select v.id, v.name from 
//     SFMNomenclatureBundle:ValeurNomenclature v join v.typeNomenclature t
//    where t.codeType =:codetype and v.code !=:code')
// ->setParameters(array('codetype'=>'code05','code'=>'T04'))
// ->getArrayResult();

public function findNoeuds(){

    $query= $this->createQueryBuilder('s');
    $query->select("PARTIAL s.{id}")
             ->addSelect("PARTIAL site.{ id,name }")
            ->join('s.site','site');
            // ->addSelect("PARTIAL fiche.{ id,label }")
            // ->join('s.fiche','fiche');

    return $query->orderBy('s.id', 'DESC')
                    ->getQuery()
                    ->getArrayResult(AbstractQuery::HYDRATE_ARRAY);

}
}
