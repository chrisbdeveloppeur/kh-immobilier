<?php

namespace App\Repository;

use App\Entity\Quittance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quittance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quittance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quittance[]    findAll()
 * @method Quittance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuittanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quittance::class);
    }

    // /**
    //  * @return Quittance[] Returns an array of Quittance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quittance
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
