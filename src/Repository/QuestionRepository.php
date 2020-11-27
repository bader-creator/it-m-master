<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('q');

        if($search_input!=""){
            $query->join('q.choix','c')
           
                  ->where('q.label LIKE :word or c.label LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('q.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    public function findUserDetails($id){

        return $this->createQueryBuilder('q')
                    ->select(['q','c'])
                    ->join('q.choix','c')
                    ->where('q.id =:id')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getArrayResult();

    }

    

}
