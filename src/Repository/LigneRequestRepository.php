<?php

namespace App\Repository;

use App\Entity\LigneRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LigneRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneRequest[]    findAll()
 * @method LigneRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LigneRequest::class);
    }

    // /**
    //  * @return LigneRequest[] Returns an array of LigneRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LigneRequest
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
