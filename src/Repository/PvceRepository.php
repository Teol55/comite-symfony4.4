<?php

namespace App\Repository;

use App\Entity\Pvce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pvce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pvce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pvce[]    findAll()
 * @method Pvce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PvceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pvce::class);
    }

    // /**
    //  * @return Pvce[] Returns an array of Pvce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pvce
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
