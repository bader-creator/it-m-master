<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

        public function findBySearchInput($search_input){

        $query= $this->createQueryBuilder('u');

        if($search_input!=""){
            $query->join('u.fonction','f')
                  ->join('u.groupe','g')
                  ->where('u.username LIKE :word or u.firstName LIKE :word or u.lastName LIKE :word or u.phone LIKE :word 
                     or u.email LIKE :word or f.name LIKE :word or g.name LIKE :word')
                  ->setParameter('word', '%'.$search_input.'%');
        }

        return $query->orderBy('u.id', 'DESC')
                        ->getQuery()
                        ->getResult();

    }

    public function findUserDetails($id){

        return $this->createQueryBuilder('u')
                    ->select(['u','g','f'])
                    ->join('u.groupe','g')
                    ->join('u.fonction','f')
                    ->where('u.id =:id')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getArrayResult();

    }


}
