<?php

namespace App\Repository;

use App\Entity\ImageSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
/**
 * @method ImageSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageSite[]    findAll()
 * @method ImageSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageSite::class);
    }

    // /**
    //  * @return ImageSite[] Returns an array of ImageSite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageSite
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findPhotos($question_id){

        $query= $this->createQueryBuilder('s');

        $query->select("PARTIAL s.{id, path,dateInsertion}")
                ->addSelect("PARTIAL reponse.{ id,question }")
                ->join('s.reponse','reponse')
                ->join('reponse.question','question')
                ->where('question.id = :question_id ')
                ->setParameter('question_id' ,$question_id)
                ->addSelect("PARTIAL userCreator.{ id,lastName,firstName,path,phone }")
                ->join('s.userCreator','userCreator');

        return $query->orderBy('s.id', 'DESC')
                        ->getQuery()
                        ->getResult(AbstractQuery::HYDRATE_ARRAY);

    }
}
