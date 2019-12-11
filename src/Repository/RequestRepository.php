<?php

namespace App\Repository;

use App\Entity\Request;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Request|null find($id, $lockMode = null, $lockVersion = null)
 * @method Request|null findOneBy(array $criteria, array $orderBy = null)
 * @method Request[]    findAll()
 * @method Request[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Request::class);
    }
    public function countByNbTicket($ticket,User $user)
    {

        return $this->createQueryBuilder('r')
            ->innerJoin('r.ligneRequest','l')
            ->select('SUM(l.NbTicket)')
            ->andWhere('l.ticket = :ticketId')
            ->andWhere('r.user= :userId')
//            ->andWhere('t.dateVisit>= :date_start')
//            ->andWhere('t.dateVisit <= :date_end')
//            ->setParameter('date_start', $date->format('Y-m-d 00:00:00'))
//            ->setParameter('date_end',   $date->format('Y-m-d 23:59:59'))
            ->setParameter('ticketId',$ticket)
            ->setParameter('userId',$user->getId())
            ->getQuery()
            ->getSingleScalarResult();

    }
    // /**
    //  * @return Request[] Returns an array of Request objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Request
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
