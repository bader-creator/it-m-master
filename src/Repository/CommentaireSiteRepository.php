<?php

namespace App\Repository;

use App\Entity\CommentaireSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
/**
 * @method CommentaireSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentaireSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentaireSite[]    findAll()
 * @method CommentaireSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentaireSite::class);
    }

    // /**
    //  * @return CommentaireSite[] Returns an array of CommentaireSite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentaireSite
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findCommentaires($question_id){

        $query= $this->createQueryBuilder('s');

        $query->select("PARTIAL s.{id, comment,dateCommentaire}")
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
