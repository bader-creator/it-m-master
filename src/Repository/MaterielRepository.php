<?php

namespace App\Repository;

use App\Entity\Materiel;
use App\Entity\Mission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;

/**
 * @method Materiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiel[]    findAll()
 * @method Materiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    // /**
    //  * @return Materiel[] Returns an array of Materiel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Materiel
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('m');

        if($search_input!=""){
            $query->where('m.mission.site.name or m.stock.nomProduit LIKE :word  LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('m.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }
   
    public function findMaterialByMission($id){

        return $this->createQueryBuilder('m')
                    ->select(['m','m1'])
                    ->join('m.mission','m1')
                    ->where('m.id =:id')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getOneOrNullResult();
                   
    }

   
    public function finddMaterialByMission($mission)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.mission = :val')
            ->setParameter('val', $mission)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findMaterialByMissionAndStock($mission,$stock)
    {
        return $this->createQueryBuilder('m')
            ->Where('m.mission = :val')
            ->AndWhere('m.stock = :val2')
            ->setParameters(array('val'=> $mission,'val2'=> $stock))
            ->getQuery()
            ->getResult();
    }
    public function findMaterials(){

        $query= $this->createQueryBuilder('s');

        $query->select("PARTIAL s.{id,  quantiteSortie}")
                ->addSelect("PARTIAL stock.{ id,nomProduit }")
                ->join('s.stock','stock');
            //     ->Where('s.id = :id')
            //    ->setParameter('id', $id);
             

        return $query->orderBy('s.id', 'DESC')
                        ->getQuery()
                        ->getResult(AbstractQuery::HYDRATE_ARRAY);

    }
    public function findMaterialsByMission($mission_id){

        $query= $this->createQueryBuilder('s');

        $query->select("PARTIAL s.{id,  quantiteSortie}")
                ->addSelect("PARTIAL stock.{ id,nomProduit }")
                ->join('s.stock','stock')
                ->addSelect("PARTIAL mission.{ id }")
                ->join('s.mission','mission')
                ->where('mission.id = :mission_id ')
                ->setParameter('mission_id' ,$mission_id);
              
             

        return $query->orderBy('s.id', 'DESC')
                        ->getQuery()
                        ->getResult(AbstractQuery::HYDRATE_ARRAY);

    }
    
}
